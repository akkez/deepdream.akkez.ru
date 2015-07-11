<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Algorithm;
use app\models\Picture;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\UploadForm;
use yii\web\HttpException;
use yii\web\UploadedFile;

class SiteController extends Controller
{
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex()
	{
		$pendingImageCount = Picture::find()->where(['state' => 'new'])->count();
		$pendingPictures   = Picture::find()->where(['state' => 'pending'])->orderBy('id ASC')->all();
		$lastReady         = Picture::find()->where(['state' => 'ready'])->orderBy('id DESC')->limit(32)->all();

		return $this->render('index', [
			'pendingImageCount' => $pendingImageCount,
			'pendingPictures'   => $pendingPictures,
			'lastPictures'      => $lastReady,
		]);
	}

	public function actionUpload()
	{
		$readyPicsCount = Picture::find()->where(['state' => 'ready'])->count();
		$lastPictures   = Picture::find()->where(['state' => 'ready'])->orderBy('updated ASC')->limit(50)->offset($readyPicsCount - 50)->all();
		$avgPictureTime = 0;
		if (count($lastPictures) > 1)
		{
			$summ = 0;
			for ($i = 1; $i < count($lastPictures); $i++)
			{
				$diff = strtotime($lastPictures[$i]->updated) - strtotime($lastPictures[$i - 1]->updated);
				$summ += $diff;
			}
			$avgPictureTime = intval($summ / (count($lastPictures) - 1));
		}
		$readyTime   = $avgPictureTime * Picture::find()->where(['state' => 'new'])->count();
		$myPictureDP = new ActiveDataProvider([
			'query' => Picture::find()->where(['state' => 'new', 'ip' => Yii::$app->getRequest()->getUserIP()]),
			'sort'  => false,
		]);
		$lastPending = Picture::find()->where(['state' => 'pending'])->orderBy('id DESC')->one();
		if ($lastPending == null)
		{
			$lastPending = Picture::find()->where(['state' => 'ready'])->orderBy('id DESC')->one();
		}
		$pendingPicsCount = Picture::find()->where(['state' => 'new'])->count();
		$algorithms       = [];
		$algos            = Algorithm::find()->orderBy('count DESC')->all();
		foreach ($algos as $algo)
		{
			if (count($algorithms) == 0)
			{
				$algorithms[$algo->getPrimaryKey()] = $algo->name . ' (default, ' . $algo->count . ' pics)';
			}
			else
			{
				$algorithms[$algo->getPrimaryKey()] = $algo->name . ' (' . $algo->count . ' pics)';
			}
		}
		$viewData = [
			'readyTime'        => $readyTime,
			'myPictureDP'      => $myPictureDP,
			'avgPictureTime'   => $avgPictureTime,
			'lastPendingId'    => $lastPending->id,
			'pendingPicsCount' => $pendingPicsCount,
			'algorithms'       => $algorithms,
			'algos'            => $algos,
		];

		$model = new UploadForm();
		if ($model->load(Yii::$app->request->post()))
		{
			$image        = UploadedFile::getInstance($model, 'image');
			$model->image = $image;
			if ($model->validate() && $model->check())
			{
				$size = getimagesize($image->tempName);
				list($width, $height, $type) = $size;
				if ($type == IMAGETYPE_JPEG)
				{
					$img = imagecreatefromjpeg($image->tempName);
				}
				else if ($type == IMAGETYPE_PNG)
				{
					$img = imagecreatefrompng($image->tempName);
				}
				else
				{
					throw new HttpException(400, 'Bad image');
				}
				$srcName  = Helper::gen_uuid() . '.jpg';
				$filename = \Yii::$app->basePath . '/web/images/' . $srcName;

				$k = 650;
				if (!($width <= $k && $height <= $k))
				{
					$minSide = (int)(min($width, $height) * $k / max($width, $height));
					list($newWidth, $newHeight) = ($width > $height) ? [$k, $minSide] : [$minSide, $k];
					$newImage = imagecreatetruecolor($newWidth, $newHeight);
					imagecopyresampled($newImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
					imagejpeg($newImage, $filename, 100);
				}
				else
				{
					imagejpeg($img, $filename, 100);
				}

				$hash  = sha1(file_get_contents($filename));
				$count = Picture::find()->where(['hash' => $hash, 'algorithmId' => $model->algoId])->all();
				if (count($count) > 0)
				{
					unlink($filename);
					$first = $count[0];
					Yii::$app->getSession()->setFlash('success', 'This image was already processed.');

					return $this->redirect('/picture/' . $first->getPrimaryKey());
				}
				$algo = Algorithm::find()->where(['id' => $model->algoId])->one();

				$picture              = new Picture();
				$picture->email       = $model->email;
				$picture->ip          = \Yii::$app->getRequest()->getUserIP();
				$picture->source      = $srcName;
				$picture->output      = null;
				$picture->state       = 'new';
				$picture->hash        = $hash;
				$picture->status      = 0;
				$picture->algorithm   = $algo->name;
				$picture->algorithmId = $model->algoId;
				$picture->save();

				$algo->count += 1;
				$algo->save();

				\Yii::$app->getSession()->setFlash('success', 'Your image were successfully uploaded. Converted image will be ready after ~' . Helper::formatHourAndMin($readyTime) . ' and sent on your email. Thank you!');

				return $this->redirect('/picture/' . $picture->getPrimaryKey());
			}
		}

		$viewData['model'] = $model;

		return $this->render('upload', $viewData);
	}

	public function actionAbout()
	{
		return $this->render('about');
	}

	public function actionPong($id)
	{
		$picture = Picture::findOne(['id' => $id, 'state' => 'finishing']);
		if ($picture == null)
		{
			throw new HttpException(404, 'There are no memes here');
		}
		/* @var \app\models\Picture $picture */
		$path = \Yii::$app->basePath . '/web/ready/' . $picture->output;
		$img  = imagecreatefrompng($path); #omg prosto omg
		unlink($path);
		imagejpeg($img, $path, 100);

		$picture->state = 'ready';
		$picture->save();

		\Yii::$app->mailer->compose('result', ['picture' => $picture])->setTo($picture->email)->setSubject('Your DeepDream picture')->send();
		echo 'ok';
	}

	public function actionCheck()
	{
		$pictures = Picture::find()->where(['hash' => ''])->all();
		/* @var \app\models\Picture[] $pictures */
		foreach ($pictures as $pic)
		{
			$filename = \Yii::$app->basePath . '/web/images/' . $pic->source;
			if (!file_exists($filename))
			{
				echo 'file ' . $filename . ' not exists. ';
				continue;
			}
			$hash      = sha1(file_get_contents($filename));
			$pic->hash = $hash;
			echo 'file ' . $filename . ': hash = ' . $hash . '. ';
			$pic->save();
		}
		echo 'done.';
	}

	public function actionStatus()
	{
		$pendingImageCount = Picture::find()->where(['state' => 'new'])->count();
		$pendingPictures   = Picture::find()->where(['state' => 'pending'])->orderBy('id ASC')->all();

		$response = [];
		foreach ($pendingPictures as $pic)
		{
			$progress   = (int)(100.0 * $pic->status / 40);
			$response[] = [
				'id'       => $pic->id,
				'source'   => '/images/' . $pic->source,
				'progress' => $progress,
			];
		}

		echo json_encode(['images' => $response, 'queue' => $pendingImageCount]);
	}
}

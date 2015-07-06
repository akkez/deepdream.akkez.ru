<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Picture;
use Yii;
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
		$lastPicture       = Picture::find()->where(['state' => 'pending'])->orderBy('id ASC')->one();

		return $this->render('index', [
			'pendingImageCount' => $pendingImageCount,
			'lastPicture'       => $lastPicture,
		]);
	}

	public function actionUpload()
	{
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
				$count = Picture::find()->where(['hash' => $hash])->count();
				if ($count > 0)
				{
					$model->addError('image', 'Sorry, image that you requested are ALREADY in queue. Please wait and/or look into gallery. Thank you!');
					unlink($filename);

					return $this->render('upload', [
						'model' => $model
					]);
				}

				$picture         = new Picture();
				$picture->email  = $model->email;
				$picture->ip     = \Yii::$app->getRequest()->getUserIP();
				$picture->source = $srcName;
				$picture->output = null;
				$picture->state  = 'new';
				$picture->hash   = $hash;
				$picture->status = 0;
				$picture->save();

				\Yii::$app->getSession()->setFlash('success', 'Your image were successfully uploaded. Converted image will be sent on your email. Thank you!');

				return $this->redirect('/');
			}
		}

		return $this->render('upload', [
			'model' => $model
		]);
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
}

<?php

namespace app\controllers;

use app\helpers\Helper;
use app\models\Picture;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;
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
		return $this->render('index');
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

				$picture         = new Picture();
				$picture->email  = $model->email;
				$picture->ip     = \Yii::$app->getRequest()->getUserIP();
				$picture->source = $srcName;
				$picture->output = null;
				$picture->state  = 'new';
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
		$picture = Picture::findOne(['id' => 1]);
		\Yii::$app->mailer
			->compose('result', ['picture' => $picture])->setTo('akke.podstavnoy@gmail.com')
			->send();

		return $this->render('about');
	}
}

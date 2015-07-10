<?php
/**
 * Created by PhpStorm.
 * Date: 11.07.2015
 * Time: 1:30
 */

namespace app\controllers;

use app\models\Picture;
use yii\web\Controller;
use yii\web\HttpException;

class PictureController extends Controller
{
	public function actionView($id)
	{
		$picture = Picture::find()->where(['id' => $id])->andWhere(['!=', 'state', 'hidden'])->one();
		if ($picture == null)
		{
			throw new HttpException(404, 'Haha');
		}
		echo $this->render('view', ['picture' => $picture]);
	}
}
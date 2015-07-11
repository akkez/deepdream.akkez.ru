<?php
/**
 * Created by PhpStorm.
 * Date: 11.07.2015
 * Time: 1:30
 */

namespace app\controllers;

use app\models\Like;
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

	public function actionLike()
	{
		if (!\Yii::$app->getRequest()->getIsPost())
		{
			return 'try agaen';
		}
		if (\Yii::$app->getRequest()->getHeaders()->get('x-like') !== 'True')
		{
			return 'try agaen';
		}
		$pictureId = \Yii::$app->getRequest()->post('p');
		$hash      = \Yii::$app->getRequest()->post('h');
		$picture   = Picture::find()->where(['id' => $pictureId])->one();
		/* @var Picture $picture */
		if ($picture == null)
		{
			return 'try agaen';
		}
		if ($picture->getLikeHash() !== $hash)
		{
			return 'try agaen';
		}
		$like = Like::find()->where(['ip' => \Yii::$app->getRequest()->getUserIP(), 'pictureId' => $picture->getPrimaryKey()])->count();
		if ($like > 0)
		{
			return 'pls stap';
		}
		$like            = new Like();
		$like->ip        = \Yii::$app->getRequest()->getUserIP();
		$like->pictureId = $picture->getPrimaryKey();
		$like->save();

		$picture->likeCount += 1;
		$picture->save();

		return '+'. $picture->likeCount;
	}
}
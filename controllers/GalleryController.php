<?php
/**
 * Created by PhpStorm.
 * Date: 06.07.2015
 * Time: 14:00
 */

namespace app\controllers;

use app\models\Algorithm;
use app\models\Picture;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\HttpException;

class GalleryController extends Controller
{

	public function actionIndex($algorithmId = null)
	{
		if ($algorithmId != null && Algorithm::find()->where(['id' => $algorithmId])->one() == null)
		{
			throw new HttpException(404, 'Haha but no');
		}
		$query = Picture::find()->where(['state' => 'ready']);
		if ($algorithmId != null)
		{
			$query = $query->andWhere(['algorithmId' => $algorithmId]);
		}
		$countQuery           = clone $query;
		$pages                = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
		$pages->pageSizeParam = false;
		$pictures             = $query->offset($pages->offset)->limit($pages->limit)->orderBy('id desc')->all();
		$algorithms           = Algorithm::find()->orderBy('count DESC')->all();

		return $this->render('index', [
			'pictures'   => $pictures,
			'paginator'  => $pages,
			'algorithms' => $algorithms,
			'algorithmId' => $algorithmId,
		]);
	}

	public function actionQueue()
	{
		$query                = Picture::find()->where(['state' => 'new']);
		$countQuery           = clone $query;
		$pages                = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
		$pages->pageSizeParam = false;
		$pictures             = $query->offset($pages->offset)->limit($pages->limit)->orderBy('id asc')->all();

		return $this->render('queue', [
			'pictures'  => $pictures,
			'paginator' => $pages,
		]);
	}
}
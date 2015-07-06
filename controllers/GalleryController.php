<?php
/**
 * Created by PhpStorm.
 * Date: 06.07.2015
 * Time: 14:00
 */

namespace app\controllers;

use app\models\Picture;
use yii\data\Pagination;
use yii\web\Controller;

class GalleryController extends Controller
{

	public function actionIndex()
	{
		$query                = Picture::find()->where(['state' => 'ready']);
		$countQuery           = clone $query;
		$pages                = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 100]);
		$pages->pageSizeParam = false;
		$pictures             = $query->offset($pages->offset)->limit($pages->limit)->orderBy('id desc')->all();

		return $this->render('index', [
			'pictures'      => $pictures,
			'paginator' => $pages,
		]);
	}
}
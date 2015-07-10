<?php
/**
 * Created by PhpStorm.
 * Date: 10.07.2015
 * Time: 14:46
 */

namespace app\commands;

use app\models\Picture;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

class WatchdogController extends Controller
{
	public function actionClear()
	{
		$pictures = Picture::find()->where(['<=', 'updated', new Expression('NOW() - INTERVAL 5 MINUTE')])->andWhere(['state' => 'pending'])->all();
		/* @var Picture[] $pictures */
		foreach ($pictures as $pic)
		{
			echo "Pending picture #" . $pic->id . " was detached and become 'new' now.\n";
			$pic->state            = 'new';
			$pic->save();
		}
		echo "Okay.\n";
	}
}
<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_123621_AlgoData extends Migration
{
	public function up()
	{
		$algo        = new \app\models\Algorithm();
		$algo->name  = 'inception_4c/output';
		$algo->count = \app\models\Picture::find()->count();
		$algo->save();
		$pk = $algo->getPrimaryKey();

		\app\models\Picture::updateAll(['algorithm' => 'inception_4c/output', 'algorithmId' => $pk]);

		$other = [
			'conv2/3x3',
			'conv2/3x3_reduce',
			'conv2/norm2',
			'inception_3a/3x3',
			'inception_3a/3x3_reduce',
			'inception_3a/5x5_reduce',
			'inception_3a/output',
			'inception_3a/pool',
			'inception_3b/1x1',
			'inception_3b/3x3',
			'inception_3b/3x3_reduce',
			'inception_3b/5x5_reduce',
			'inception_3b/output',
			'inception_3b/pool',
			'inception_3b/pool_proj',
			'inception_4a/1x1',
			'inception_4a/3x3_reduce',
			'inception_4a/5x5_reduce',
			'inception_4b/pool',
		];

		foreach ($other as $name)
		{
			$algo        = new \app\models\Algorithm();
			$algo->count = 0;
			$algo->name  = $name;
			$algo->save();
		}
	}

	public function down()
	{
		\app\models\Algorithm::deleteAll();
		echo "Okay.\n";
	}
}

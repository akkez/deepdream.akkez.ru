<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_123225_PictureAlgo extends Migration
{
	public function up()
	{
		$this->addColumn('Picture', 'algorithm', Schema::TYPE_STRING . ' NOT NULL AFTER `hash`');
		$this->addColumn('Picture', 'algorithmId', Schema::TYPE_INTEGER . ' AFTER `algorithm`');
	}

	public function down()
	{
		$this->dropColumn('Picture', 'algorithm');
		$this->dropColumn('Picture', 'algorithmId');
	}
}

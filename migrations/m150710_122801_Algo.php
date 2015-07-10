<?php

use yii\db\Schema;
use yii\db\Migration;

class m150710_122801_Algo extends Migration
{
	public function up()
	{
		$this->createTable('Algorithm', [
			'id'    => Schema::TYPE_PK,
			'name'  => Schema::TYPE_STRING . ' NOT NULL',
			'count' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0'
		]);
	}

	public function down()
	{
		$this->dropTable('Algorithm');
	}
}

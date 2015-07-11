<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_174152_Key extends Migration
{
	public function up()
	{
		$this->createTable('Key', [
			'id'       => Schema::TYPE_PK,
			'value'    => Schema::TYPE_STRING . ' NOT NULL',
			'used'     => Schema::TYPE_INTEGER . ' NOT NULL',
			'count'    => Schema::TYPE_INTEGER . ' NOT NULL',
			'priority' => Schema::TYPE_INTEGER . ' NOT NULL',
			'created'  => Schema::TYPE_TIMESTAMP,
		]);
	}

	public function down()
	{
		$this->dropTable('Key');
	}
}

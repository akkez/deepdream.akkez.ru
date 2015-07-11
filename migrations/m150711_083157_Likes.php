<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_083157_Likes extends Migration
{
	public function up()
	{
		$this->createTable('Like', [
			'id'        => Schema::TYPE_PK,
			'ip'        => Schema::TYPE_STRING . ' NOT NULL',
			'pictureId' => Schema::TYPE_INTEGER . ' NOT NULL',
			'created'   => Schema::TYPE_TIMESTAMP,
		]);
	}

	public function down()
	{
		$this->dropTable('Like');
	}
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_175811_KeyComment extends Migration
{
	public function up()
	{
		$this->addColumn('Key', 'comment', Schema::TYPE_STRING . ' NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('Key', 'comment');
	}
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_173544_PicturePriority extends Migration
{
	public function up()
	{
		$this->addColumn('Picture', 'priority', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 AFTER likeCount');
	}

	public function down()
	{
		$this->dropColumn('Picture', 'priority');
	}
}

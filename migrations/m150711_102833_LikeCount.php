<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_102833_LikeCount extends Migration
{
	public function up()
	{
		$this->addColumn('Picture', 'likeCount', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 AFTER algorithmId');
	}

	public function down()
	{
		$this->dropColumn('Picture', 'likeCount');
	}
}

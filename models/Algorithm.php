<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Algorithm".
 *
 * @property integer $id
 * @property string $name
 * @property integer $count
 */
class Algorithm extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Algorithm';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['count'], 'integer'],
			[['name'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'name'  => 'Name',
			'count' => 'Count',
		];
	}
}

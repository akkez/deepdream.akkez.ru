<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Like".
 *
 * @property integer $id
 * @property string $ip
 * @property integer $pictureId
 * @property string $created
 */
class Like extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Like';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['ip', 'pictureId'], 'required'],
			[['pictureId'], 'integer'],
			[['created'], 'safe'],
			[['ip'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'ip'        => 'Ip',
			'pictureId' => 'Picture ID',
			'created'   => 'Created',
		];
	}

	public function beforeSave($insert)
	{
		if ($this->isNewRecord)
		{
			$this->created = new \yii\db\Expression('NOW()');
		}

		return parent::beforeSave($insert);
	}
}

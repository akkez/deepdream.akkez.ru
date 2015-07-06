<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Picture".
 *
 * @property integer $id
 * @property string $email
 * @property string $state
 * @property string $ip
 * @property string $source
 * @property string $output
 * @property integer $status
 */
class Picture extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Picture';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['email', 'state', 'ip', 'source'], 'required'],
			[['status'], 'integer'],
			[['email', 'state', 'ip', 'source', 'output'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'     => 'ID',
			'email'  => 'Email',
			'state'  => 'State',
			'ip'     => 'Ip',
			'source' => 'Source',
			'output' => 'Output',
			'status' => 'Status',
		];
	}

	public function beforeSave($insert)
	{
		if ($this->isNewRecord)
		{
			$this->created = new \yii\db\Expression('NOW()');
		}
		$this->updated = new \yii\db\Expression('NOW()');

		return parent::beforeSave($insert);
	}

}

<?php
/**
 * Created by PhpStorm.
 * Date: 06.07.2015
 * Time: 12:06
 */

namespace app\models;

use yii\base\Model;

class UploadForm extends Model
{
	public $email;
	public $image;

	public function rules()
	{
		return [
			[['email'], 'required'],
			['email', 'email'],
			['image', 'file', 'skipOnEmpty' => false, 'mimeTypes' => 'image/png, image/jpeg']
		];
	}

	public function attributeLabels()
	{
		return ['email' => 'Email', 'image' => 'Your image'];
	}

	public function check()
	{
		$count = Picture::find()->where(['ip' => \Yii::$app->getRequest()->getUserIP()])->andWhere(['state' => 'new'])->count();
		if ($count >= 3)
		{
			$this->addError('image', 'Sorry, you cannot upload more than 3 images while they in queue. Try later.');

			return false;
		}

		return true;
	}
}
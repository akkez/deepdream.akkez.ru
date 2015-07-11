<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Key".
 *
 * @property integer $id
 * @property string $value
 * @property integer $used
 * @property integer $count
 * @property integer $priority
 * @property string $created
 */
class Key extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'used', 'count'], 'required'],
            [['used', 'count'], 'integer'],
            [['created'], 'safe'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'used' => 'Used',
            'count' => 'Count',
            'created' => 'Created',
        ];
    }
}

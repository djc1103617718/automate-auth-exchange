<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user_detail}}".
 *
 * @property integer $id
 * @property integer $phone
 * @property integer $user_id
 * @property string $phone_brand
 * @property string $activation_code
 * @property integer $code_expired_time
 * @property integer $created_time
 * @property integer $updated_time
 */
class UserDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_detail}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => 'updated_time',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'activation_code'], 'required'],
            [['id', 'user_id', 'code_expired_time', 'created_time', 'updated_time'], 'integer'],
            ['phone', 'string', 'length' => 11],
            ['phone', 'trim'],
            ['phone', 'phoneRule'],
            [['activation_code'], 'string', 'max' => 64],
        ];
    }

    public function phoneRule()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'user_id' => 'User ID',
            'activation_code' => 'Activation Code',
            'code_expired_time' => 'Code Expired Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

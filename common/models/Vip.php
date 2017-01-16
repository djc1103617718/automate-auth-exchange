<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%vip}}".
 *
 * @property integer $vip_id
 * @property string $vip_name
 * @property string $description
 * @property integer $created_time
 * @property integer $updated_time
 */
class Vip extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip}}';
    }

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
            [['vip_name'], 'required'],
            [['created_time', 'updated_time', 'status'], 'integer'],
            [['description'], 'string', 'max' => 256],
            [['vip_name'], 'string', 'max' => 32],
            [['vip_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vip_id' => Yii::t('app', 'VIP ID'),
            'vip_name' => Yii::t('app', 'VIP名称'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '修改时间'),
            'description' => Yii::t('app', '描述'),
            'status' => Yii::t('app', '状态'),
        ];
    }
}

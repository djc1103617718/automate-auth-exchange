<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%user_xunruibizhi}}".
 *
 * @property integer $wechatid
 * @property string $phone
 * @property string $regist_time
 * @property string $city
 * @property string $deviceid
 * @property integer $status
 * @property string $extra_field
 * @property string $updated_time
 */
class UserXunruibizhi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_xunruibizhi}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('weChatDb');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regist_time', 'updated_time'], 'safe'],
            [['status'], 'integer'],
            [['phone'], 'string', 'max' => 15],
            [['city'], 'string', 'max' => 20],
            [['deviceid'], 'string', 'max' => 64],
            [['extra_field'], 'string', 'max' => 15000],
            [['deviceid', 'phone'], 'unique', 'targetAttribute' => ['deviceid', 'phone'], 'message' => 'The combination of Phone and Deviceid has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wechatid' => Yii::t('app', 'ID'),
            'phone' => Yii::t('app', '手机号'),
            'regist_time' => Yii::t('app', '注册时间'),
            'city' => Yii::t('app', '城市'),
            'deviceid' => Yii::t('app', '设备ID'),
            'status' => Yii::t('app', '状态'),
            'extra_field' => Yii::t('app', 'Extra Field'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }
}

<?php

namespace common\models\wechatdb;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%wechat_online_time_log}}".
 *
 * @property integer $log_id
 * @property string $account
 * @property integer $device_id
 * @property integer $online_time
 * @property integer $created_time
 * @property integer $statistics_time
 */
class WechatOnlineTimeLog extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat_online_time_log}}';
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
            [['account', 'device_id', 'online_time', 'created_time', 'statistics_time'], 'required'],
            [['online_time', 'created_time', 'statistics_time'], 'integer'],
            [['account'], 'string', 'max' => 64],
            [['device_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'ID'),
            'account' => Yii::t('app', '账号'),
            'device_id' => Yii::t('app', '设备ID'),
            'online_time' => Yii::t('app', '在线时长'),
            'created_time' => Yii::t('app', '创建时间'),
            'statistics_time' => Yii::t('app', '统计时间'),
        ];
    }
}

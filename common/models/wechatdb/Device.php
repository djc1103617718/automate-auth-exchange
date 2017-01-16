<?php

namespace common\models\wechatdb;

use backend\models\wechatdb\WeChat;
use Yii;

/**
 * This is the model class for table "{{%devices}}".
 *
 * @property integer $nouseid
 * @property string $deviceid
 * @property string $last_connect_time
 * @property string $province
 * @property integer $city
 * @property integer $last_job_type
 * @property string $last_job_param
 * @property string $account
 * @property string $wechat
 * @property integer $vpnid
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%devices}}';
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
            [['deviceid'], 'required'],
            [['last_connect_time'], 'safe'],
            [['city', 'last_job_type', 'vpnid'], 'integer'],
            [['deviceid', 'account'], 'string', 'max' => 64],
            [['province'], 'string', 'max' => 16],
            [['last_job_param'], 'string', 'max' => 256],
            [['wechat'], 'string', 'max' => 11],
            [['localip'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nouseid' => Yii::t('app', 'Nouse ID'),
            'deviceid' => Yii::t('app', '设备ID'),
            'last_connect_time' => Yii::t('app', '最后连接时间'),
            'province' => Yii::t('app', '省'),
            'city' => Yii::t('app', '市'),
            'last_job_type' => Yii::t('app', '最后执行自动化代号'),
            'last_job_param' => Yii::t('app', 'Last Job Param'),
            'account' => Yii::t('app', '账号'),
            'wechat' => Yii::t('app', '微信号'),
            'vpnid' => Yii::t('app', 'VPN ID'),
            'appleid' => Yii::t('app', 'Apple ID'),
            'localip' => Yii::t('app', 'IP'),
        ];
    }

    public function getWeChats()
    {
        return $this->hasMany(WeChat::className(),['deviceid' => 'deviceid']);
    }
}

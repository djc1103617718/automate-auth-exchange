<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%vpn_usage}}".
 *
 * @property integer $nouseid
 * @property string $deviceid
 * @property string $ipaddr
 * @property string $access_time
 * @property integer $vpnid
 */
class VpnUsage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vpn_usage}}';
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
            [['access_time'], 'safe'],
            [['vpnid'], 'required'],
            [['vpnid'], 'integer'],
            [['deviceid'], 'string', 'max' => 64],
            [['ipaddr'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nouseid' => Yii::t('app', 'ID'),
            'deviceid' => Yii::t('app', '设备ID'),
            'ipaddr' => Yii::t('app', 'IP地址'),
            'access_time' => Yii::t('app', '访问时间'),
            'vpnid' => Yii::t('app', 'VPN ID'),
        ];
    }
}

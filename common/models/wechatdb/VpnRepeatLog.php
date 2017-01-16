<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%vpn_repeat_log}}".
 *
 * @property integer $log_id
 * @property string $vpn_name
 * @property double $repetition_rate
 * @property integer $vpn_id
 * @property string $vpn_ip
 * @property integer $city
 * @property string $username
 * @property string $password
 * @property integer $used
 * @property integer $statistics_time
 * @property integer $created_time
 */
class VpnRepeatLog extends \yii\db\ActiveRecord
{
    const USED_TYPE_TRUE = 1;
    const USED_TYPE_FALSE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vpn_repeat_log}}';
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
            [['repetition_rate'], 'number'],
            [['vpn_id', 'statistics_time', 'created_time'], 'required'],
            [['vpn_id', 'city', 'statistics_time', 'created_time'], 'integer'],
            [['vpn_name'], 'string', 'max' => 64],
            [['vpn_ip', 'username', 'password'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'ID'),
            'vpn_name' => Yii::t('app', 'VPN名'),
            'repetition_rate' => Yii::t('app', '重复率'),
            'vpn_id' => Yii::t('app', 'VPN ID'),
            'vpn_ip' => Yii::t('app', 'VPN IP'),
            'city' => Yii::t('app', '城市'),
            'username' => Yii::t('app', '账号'),
            'password' => Yii::t('app', '密码'),
            'statistics_time' => Yii::t('app', '统计时间'),
            'created_time' => Yii::t('app', '创建时间'),
        ];
    }
}

<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%vpn}}".
 *
 * @property integer $vpnid
 * @property string $vpnname
 * @property integer $city
 * @property string $vpnip
 * @property string $username
 * @property string $password
 * @property integer $used
 */
class Vpn extends \yii\db\ActiveRecord
{
    const USED_CONDITION_TRUE = 1;
    const USED_CONDITION_FALSE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vpn}}';
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
            [['city', 'used'], 'integer'],
            [['vpnname'], 'string', 'max' => 64],
            [['vpnip', 'username', 'password'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vpnid' => Yii::t('app', 'VPN ID'),
            'vpnname' => Yii::t('app', 'VPN名'),
            'city' => Yii::t('app', '城市'),
            'vpnip' => Yii::t('app', 'VPN IP'),
            'username' => Yii::t('app', '用户名'),
            'password' => Yii::t('app', '密码'),
            'used' => Yii::t('app', '使用情况'),
        ];
    }
}

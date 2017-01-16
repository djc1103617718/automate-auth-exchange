<?php

namespace common\models\wechatdb;

use backend\models\wechatdb\WeChatFriend;
use Yii;

/**
 * This is the model class for table "{{%wechat}}".
 *
 * @property integer $wechatid
 * @property string $account
 * @property string $phone
 * @property string $gender
 * @property string $nickname
 * @property string $password
 * @property string $province
 * @property string $headimg
 * @property string $regist_time
 * @property string $city
 * @property string $regist_source
 * @property string $deviceid
 * @property string $extra_field
 */
class WeChat extends \yii\db\ActiveRecord
{
    /**
     * sex
     */
    const GENDER_MAN = 0;
    const GENDER_WOMEN = 1;

    const STATUS_NORMAL = 1;
    const STATUS_LOCK = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat}}';
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
            [['gender', 'status', 'job_num', 'commission', 'updated_time'], 'integer'],
            [['regist_time'], 'safe'],
            [['account'], 'string', 'max' => 40],
            [['phone', 'province'], 'string', 'max' => 15],
            [['nickname', 'password', 'city'], 'string', 'max' => 20],
            [['headimg'], 'string', 'max' => 128],
            [['regist_source'], 'string', 'max' => 10],
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
            'wechatid' => Yii::t('app', 'Wechatid'),
            'account' => Yii::t('app', '账号'),
            'phone' => Yii::t('app', '手机号'),
            'gender' => Yii::t('app', '性别'),
            'nickname' => Yii::t('app', '昵称'),
            'password' => Yii::t('app', '密码'),
            'province' => Yii::t('app', '省份'),
            'headimg' => Yii::t('app', '头像'),
            'regist_time' => Yii::t('app', '注册时间'),
            'city' => Yii::t('app', '城市'),
            'regist_source' => Yii::t('app', 'Regist Source'),
            'deviceid' => Yii::t('app', 'Deviceid'),
            'extra_field' => Yii::t('app', 'Extra Field'),
            'job_num' => Yii::t('app', '任务量'),
            'commission' => Yii::t('app', '佣金'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    public static function genderArray()
    {
        return
        [
            self::GENDER_MAN => '男',
            self::GENDER_WOMEN => '女',
        ];
    }

    public function getCityName()
    {
        return $this->hasOne(City::className(), ['cityid' => 'city']);
    }

}

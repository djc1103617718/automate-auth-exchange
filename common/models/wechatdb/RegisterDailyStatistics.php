<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%360_daily_statistics}}".
 *
 * @property integer $log_id
 * @property integer $register_num
 * @property integer $login_num
 * @property string $app_name
 * @property string $statistics_time
 * @property string $created_time
 */
class RegisterDailyStatistics extends \yii\db\ActiveRecord
{
    const APP_NAME_360 = '360';
    const APP_NAME_BIZHI = '统一壁纸';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%register_daily_statistics}}';
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
            [['register_num', 'statistics_time', 'created_time', 'app_name'], 'required'],
            [['register_num', 'login_num'], 'integer'],
            [['statistics_time', 'created_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', '记录ID'),
            'register_num' => Yii::t('app', '注册量'),
            'login_num' => Yii::t('app', '登录量'),
            'statistics_time' => Yii::t('app', '统计截止时间'),
            'created_time' => Yii::t('app', '创建时间'),
            'app_name' => Yii::t('app', '应用名称'),
        ];
    }
}

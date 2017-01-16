<?php

namespace common\models\wechatdb;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%account_job_log}}".
 *
 * @property integer $job_log_id
 * @property string $account
 * @property string $app_name
 * @property string $job_id
 * @property integer $job_num
 * @property integer $commission
 * @property integer $created_time
 * @property integer $updated_time
 */
class AccountJobLog extends \yii\db\ActiveRecord
{
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
    public static function tableName()
    {
        return '{{%account_job_log}}';
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
            [['account', 'app_name', 'job_id', 'statistics_time'], 'required'],
            [['job_num', 'commission', 'created_time', 'updated_time', 'statistics_time'], 'integer'],
            [['account', 'job_id'], 'string', 'max' => 64],
            [['app_name'], 'string', 'max' => 46],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_log_id' => Yii::t('app', '日志ID'),
            'account' => Yii::t('app', '账号'),
            'app_name' => Yii::t('app', '账号平台'),
            'job_id' => Yii::t('app', '任务单号'),
            'job_num' => Yii::t('app', '任务量'),
            'commission' => Yii::t('app', '佣金(元)'),
            'statistics_time' => Yii::t('app', '统计时间'),
            'created_time' => Yii::t('app', '记录创建时间'),
            'updated_time' => Yii::t('app', '记录更新时间'),
        ];
    }
}

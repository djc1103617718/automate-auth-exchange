<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%wechat_account_manage_log}}".
 *
 * @property integer $logid
 * @property string $deviceid
 * @property string $account
 * @property string $log_time
 * @property integer $job_type
 * @property string $jobid
 * @property integer $status
 * @property string $params
 */
class WeChatManageLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat_account_manage_log}}';
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
            [['deviceid', 'account', 'log_time', 'job_type'], 'required'],
            [['log_time'], 'safe'],
            [['job_type', 'status'], 'integer'],
            [['deviceid'], 'string', 'max' => 32],
            [['account'], 'string', 'max' => 46],
            [['jobid', 'params'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logid' => Yii::t('app', '养号日志ID'),
            'deviceid' => Yii::t('app', '设备ID'),
            'account' => Yii::t('app', '微信账号'),
            'log_time' => Yii::t('app', 'Log Time'),
            'job_type' => Yii::t('app', '自动化代号'),
            'jobid' => Yii::t('app', '任务单号'),
            'status' => Yii::t('app', '状态'),
            'params' => Yii::t('app', 'Params'),
        ];
    }
}

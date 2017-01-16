<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%devicelog}}".
 *
 * @property integer $logid
 * @property string $deviceid
 * @property string $app_name
 * @property string $account
 * @property string $log_time
 * @property integer $job_type
 * @property integer $jobid
 * @property integer $status
 * @property string $params
 */
class DeviceLog extends \yii\db\ActiveRecord
{
    const FINAL_YES = 1;
    const FINAL_NO = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%devicelog}}';
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
            [['deviceid', 'log_time', 'job_type', 'account', 'jobid'], 'required'],
            [['log_time'], 'safe'],
            [['jobid'], 'string', 'max' => 64],
            [['job_type', 'jobid', 'status', 'final'], 'integer'],
            [['deviceid', 'app_name'], 'string', 'max' => 32],
            [['account'], 'string', 'max' => 46],
            [['params'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logid' => Yii::t('app', '微信日志ID'),
            'deviceid' => Yii::t('app', '设备ID'),
            'app_name' => Yii::t('app', '应用名称'),
            'account' => Yii::t('app', '账号'),
            'log_time' => Yii::t('app', '执行时间'),
            'job_type' => Yii::t('app', '自动化步骤代号'),
            'jobid' => Yii::t('app', '任务ID'),
            'status' => Yii::t('app', '状态'),
            'params' => Yii::t('app', '参数'),
        ];
    }
}

<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%job_type}}".
 *
 * @property integer $type_id
 * @property integer $step_symbol
 * @property integer $app_id
 * @property integer $status
 * @property string $job_type_name
 * @property integer $created_time
 * @property integer $updated_time
 */
class JobType extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%job_type}}';
    }

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
    public function rules()
    {
        return [
            [['step_symbol','app_id', 'job_type_name'], 'required'],
            [['step_symbol', 'app_id', 'status', 'created_time', 'updated_time'], 'integer'],
            [['job_type_name'], 'string', 'max' => 32],
            [['job_type_name'], 'unique'],
            [['step_symbol', 'app_id'], 'unique', 'targetAttribute' => ['step_symbol', 'app_id'], 'message' => 'The combination of Step Symbol and App ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => Yii::t('app', 'Type ID'),
            'step_symbol' => Yii::t('app', '自动化代号'),
            'job_type_name' => Yii::t('app', '自动化名称'),
            'status' => '状态',
            'app_id' => Yii::t('app', 'APP ID'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @param int $status
     * @return string
     */
    public static function getStatusName($status)
    {
        if ($status == self::STATUS_NORMAL) {
            return '正常';
        } elseif ($status == self::STATUS_DELETE) {
            return '删除';
        } else {
            return 'error';
        }
    }
}

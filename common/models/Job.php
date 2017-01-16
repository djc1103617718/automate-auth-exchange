<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%job}}".
 *
 * @property integer $job_id
 * @property integer $price
 * @property integer $ensure_time
 * @property integer $user_id
 * @property string $job_name
 * @property string $price_introduction
 * @property integer $created_time
 * @property integer $updated_time
 * @property integer $finished_time
 * @property integer $status
 * @property integer $num
 * @property string $job_remark
 * @property integer $finished
 * @property integer $expire_time
 *
 * @property JobDetail[] $jobDetails
 */
class Job extends \yii\db\ActiveRecord
{
    /**
     * job status
     */
    const STATUS_AWAITING = 1;
    const STATUS_COMPLETE = 3;
    const STATUS_EXECUTING = 2;
    const STATUS_DRAFT = 4;
    const STATUS_DELETE = 5;
    const STATUS_NEW = 6;
    const STATUS_CANCEL = 7;

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
        return '{{%job}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'job_id', 'price_introduction'], 'required'],
            [['user_id', 'created_time', 'updated_time', 'price', 'status', 'num', 'finished', 'expire_time', 'ensure_time'], 'integer'],
            [['job_name'], 'string', 'max' => 32],
            [['job_remark'], 'string', 'max' => 256],
            [['price_rate', 'finished_time'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_id' => Yii::t('app', '任务单号'),
            'price' => Yii::t('app', '任务单价(元)'),
            'user_id' => Yii::t('app', 'User ID'),
            'job_name' => Yii::t('app', '任务名称'),
            'price_introduction' => Yii::t('app', '价格简介'),
            'price_rate' => Yii::t('app', '任务速度'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
            'ensure_time' => Yii::t('app', '审核时间'),
            'finished_time' => Yii::t('app', '完成时间'),
            'status' => Yii::t('app', '任务状态'),
            'num' => Yii::t('app', '任务量'),
            'job_remark' => Yii::t('app', '备注信息'),
            'finished' => Yii::t('app', '已完成量'),
            'expire_time' => Yii::t('app', '过期时间'),
        ];
    }

    /**
     * @return array
     */
    public static function statusArray()
    {
        return [
            self::STATUS_AWAITING => '待执行',
            self::STATUS_EXECUTING => '执行中',
            self::STATUS_COMPLETE => '已完成',
            self::STATUS_DRAFT => '草稿',
            self::STATUS_DELETE => '删除',
            self::STATUS_NEW => '新任务',
            self::STATUS_CANCEL => '审核失败'
        ];
    }

    /**
     * @param $status
     * @return false|string
     */
    public static function getStatusName($status)
    {
        $arr = array_flip(static::statusArray());
        return array_search($status, $arr);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobDetail()
    {
        return $this->hasOne(JobDetail::className(), ['job_id' => 'job_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

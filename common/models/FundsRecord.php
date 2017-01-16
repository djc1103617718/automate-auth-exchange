<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%funds_record}}".
 *
 * @property string $funds_record_id
 * @property string $record_name
 * @property integer $current_balance
 * @property integer $funds_num
 * @property string $record_source
 * @property integer $user_id
 * @property integer $type
 * @property integer $status
 * @property integer $created_time
 * @property integer $updated_time
 */
class FundsRecord extends \yii\db\ActiveRecord
{
    // 支出/充值/退款
    const TYPE_EXPENSES = 1;
    const TYPE_RECHARGE = 2;
    const TYPE_REFUND = 3;

    /**
     * 记录来源
     */
    const RECORD_SOURCE_JOB = 'job_';
    const RECORD_SOURCE_WECHAT = 'we_chat_recharge';
    const RECORD_SOURCE_ALIPAY = 'alipay_recharge';

    // 状态
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 2;

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
        return '{{%funds_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['funds_num', 'user_id', 'funds_record_id', 'record_source'], 'required'],
            [['funds_num', 'current_balance', 'user_id', 'type', 'status', 'created_time', 'updated_time'], 'integer'],
            [['record_name'], 'string', 'max' => 32],
            [['record_source'], 'string', 'max' => 46],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'funds_record_id' => Yii::t('app', '记录号'),
            'current_balance' => Yii::t('app', '当前账户余额(元)'),
            'record_name' => Yii::t('app', '记录名'),
            'record_source' => Yii::t('app', '记录来源'),
            'funds_num' => Yii::t('app', '金额(元)'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', '记录类别'),
            'status' => Yii::t('app', 'Status'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    public static function getTypeName($type)
    {
        if ($type == self::TYPE_REFUND) {
            return '退款';
        } elseif ($type == self::TYPE_EXPENSES) {
            return '消费';
        } elseif ($type == self::TYPE_RECHARGE) {
            return '充值';
        } else {
            return 'error';
        }
    }

    public static function getStatusName($status)
    {
        if ($status == self::STATUS_DELETE) {
            return '删除';
        } elseif ($status == self::STATUS_NORMAL) {
            return '正常';
        } else {
            return 'error';
        }
    }
}

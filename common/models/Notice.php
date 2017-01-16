<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $notice_id
 * @property integer $user_id
 * @property string $category_name
 * @property string $title
 * @property string $description
 * @property string $content
 * @property integer $status
 * @property integer $created_time
 * @property integer $updated_time
 */
class Notice extends \yii\db\ActiveRecord
{
    const CATEGORY_NAME_SERVER_NOTICE = '服务端消息';
    const CATEGORY_NAME_CLIENT_NOTICE = '客户端消息';
    const CATEGORY_NAME_REFUND_NOTICE = '任务退款';
    const CATEGORY_NAME_APPROVED = '审核通过';
    const CATEGORY_NAME_NEW_JOB = '新任务';
    const STATUS_UNREAD = 1;
    const STATUS_ALREADY_READ = 2;
    const STATUS_DELETE = 3;

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
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name', 'title'], 'required'],
            [['user_id', 'status', 'created_time', 'updated_time'], 'integer'],
            [['content'], 'string'],
            [['category_name', 'title'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'notice_id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'category_name' => Yii::t('app', '类别'),
            'title' => Yii::t('app', '标题'),
            'description' => Yii::t('app', '描叙'),
            'content' => Yii::t('app', '内容'),
            'status' => Yii::t('app', '状态'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusName($status)
    {
        if ($status == self::STATUS_UNREAD) {
            return '未读';
        } elseif ($status == self::STATUS_ALREADY_READ) {
            return '已读';
        } elseif ($status == self::STATUS_DELETE) {
            return '删除';
        } else {
            return 'error';
        }
    }
}

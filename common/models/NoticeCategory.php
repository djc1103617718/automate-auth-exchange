<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%notice_category}}".
 *
 * @property integer $category_id
 * @property string $category_name
 * @property integer $pid
 * @property integer $status
 * @property string $description
 * @property integer $created_time
 * @property integer $updated_time
 */
class NoticeCategory extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 2;

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
        return '{{%notice_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['pid', 'status', 'created_time', 'updated_time'], 'integer'],
            [['category_name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 126],
            [['category_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'ID'),
            'category_name' => Yii::t('app', '消息分类名'),
            'pid' => Yii::t('app', '父级ID'),
            'status' => Yii::t('app', '状态'),
            'description' => Yii::t('app', '描叙'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }
}

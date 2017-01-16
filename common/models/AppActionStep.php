<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%app_action_step}}".
 *
 * @property integer $step_id
 * @property integer $action_id
 * @property integer $step_symbol
 * @property string $job_param
 * @property integer $created_time
 * @property integer $updated_time
 */
class AppActionStep extends \yii\db\ActiveRecord
{
    /**
     * action step 状态
     */
    const STATUS_NORMAL = 1;
    const STATUS_LOCKING = 2;

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
        return '{{%app_action_step}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_id', 'step_symbol'], 'required'],
            [['action_id', 'step_symbol', 'created_time', 'updated_time'], 'integer'],
            [['job_param'], 'string', 'max' => 1250],
            [['sort'], 'integer', 'max' => 255, 'message' => "{attribute}不大于255"],
            [['step_id'], 'safe'],
            [['action_id', 'step_symbol'], 'unique', 'targetAttribute' => ['action_id', 'step_symbol'], 'message' => '该动作已经存在此自动化步骤,不能重复定义,您可以修改存在的步骤或新建新的步骤'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => AppAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'step_id' => Yii::t('app', '自动化ID'),
            'action_id' => Yii::t('app', '动作ID'),
            'step_symbol' => Yii::t('app', '自动化代号'),
            'job_param' => Yii::t('app', '自动化参数'),
            'status' => Yii::t('app', '状态'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
            'sort' => Yii::t('app', '排序'),
        ];
    }

    public static function getStatusName($status)
    {
        if ($status == self::STATUS_LOCKING) {
            return '锁定';
        } elseif ($status == self::STATUS_NORMAL) {
            return '正常';
        } else {
            return 'error';
        }
    }

    public function getAppAction()
    {
        return $this->hasOne(AppAction::className(),['action_id' => 'action_id']);
    }
}

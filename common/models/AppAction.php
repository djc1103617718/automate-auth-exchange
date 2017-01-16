<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%app_action}}".
 *
 * @property integer $action_id
 * @property integer $app_id
 * @property string $action_name
 * @property integer $category
 * @property integer $status
 * @property string $action_class_name
 * @property integer $created_time
 * @property integer $updated_time
 *
 * @property App $app
 * @property AppActionStep[] $appActionSteps
 */
class AppAction extends \yii\db\ActiveRecord
{
    /**
     * action 状态
     */
    const STATUS_NORMAL = 1;
    const STATUS_LOCKING = 2;

    const CATEGORY_COMMON_TASK = 1;
    const CATEGORY_BRUSH_LIST_TASK = 2;
    const CATEGORY_WE_CHAT_TASK = 3;

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
        return '{{%app_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'action_name', 'action_class_name', 'category'], 'required'],
            [['action_id', 'app_id', 'created_time', 'updated_time', 'category'], 'integer'],
            [['action_name'], 'string', 'max' => 24],
            [['action_class_name'], 'string', 'max' => 46],
            ['status', 'safe'],
            [['action_name'], 'unique'],
            [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => App::className(), 'targetAttribute' => ['app_id' => 'app_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => Yii::t('app', '动作ID'),
            'app_id' => Yii::t('app', 'APP ID'),
            'action_name' => Yii::t('app', '动作名'),
            'status' => Yii::t('app', '状态'),
            'category' => Yii::t('app', '类别'),
            'action_class_name' => Yii::t('app', '动作价格类名'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * category array
     */
    public static function categoryList()
    {
        return [
            self::CATEGORY_COMMON_TASK => '普通任务',
            self::CATEGORY_BRUSH_LIST_TASK => '刷榜任务',
            self::CATEGORY_WE_CHAT_TASK => '微信任务',
        ];
    }

    /**
     * @param int $category
     * @return mixed
     */
    public static function getCategoryName($category)
    {
        $arr = array_flip(static::categoryList());
        return array_search($category, $arr);
    }

    /**
     * @param int $status
     * @return string
     */
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(App::className(), ['app_id' => 'app_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppActionSteps()
    {
        return $this->hasMany(AppActionStep::className(), ['action_id' => 'action_id']);
    }
}

<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%app}}".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property integer $status
 * @property string $package_name
 * @property string $search_name
 * @property integer $created_time
 * @property integer $updated_time
 *
 * @property AppAction[] $appActions
 */
class App extends \yii\db\ActiveRecord
{
    /**
     * app
     * 状态
     */
    const STATUS_NORMAL = 1;
    const STATUS_LOCKING = 2;

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
        return '{{%app}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_name', 'package_name', 'search_name'], 'required'],
            [['app_id', 'created_time', 'updated_time'], 'integer'],
            [['app_name', 'search_name'], 'string', 'max' => 32],
            ['status', 'safe'],
            [['package_name'], 'string', 'max' => 64],
            [['app_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => Yii::t('app', 'App ID'),
            'app_name' => Yii::t('app', 'App名称'),
            'package_name' => Yii::t('app', 'App软件包名'),
            'search_name' => Yii::t('app', 'App可搜索名'),
            'status' => Yii::t('app', '状态'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    public static function getStatusName($status)
    {
        if ($status == self::STATUS_NORMAL) {
            return '正常';
        } elseif ($status == self::STATUS_LOCKING) {
            return '锁定';
        } else {
            return 'error';
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppActions()
    {
        return $this->hasMany(AppAction::className(), ['app_id' => 'app_id']);
    }
}

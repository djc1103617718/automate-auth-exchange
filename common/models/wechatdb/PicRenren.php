<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%pic_renren}}".
 *
 * @property integer $picid
 * @property string $user_id
 * @property string $name
 * @property string $gender
 * @property string $pic
 * @property integer $status
 * @property string $date
 */
class PicRenren extends \yii\db\ActiveRecord
{
    const STATUS_UNCHECKED = 1;
    const STATUS_CHECKED_SUCCESS = 2;
    const STATUS_CHECKED_FAILURE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pic_renren}}';
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
            [['status'], 'integer'],
            [['date'], 'safe'],
            [['user_id', 'name'], 'string', 'max' => 32],
            [['gender'], 'string', 'max' => 1],
            [['pic'], 'string', 'max' => 255],
            ['album_mark', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'picid' => Yii::t('app', '图片ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'name' => Yii::t('app', '用户名'),
            'gender' => Yii::t('app', '性别'),
            'pic' => Yii::t('app', '图片'),
            'status' => Yii::t('app', '状态'),
            'album_mark' => Yii::t('app', '标签'),
            'date' => Yii::t('app', '创建时间'),
        ];
    }
}

<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%content_weibo}}".
 *
 * @property integer $contentid
 * @property string $user_id
 * @property string $content
 * @property string $pic
 * @property string $gender
 * @property string $keyword
 * @property integer $status
 * @property string $date
 * @property string $person_mark
 */
class ContentWeibo extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL = 1;
    const STATUS_DELETE = 0;
    const STATUS_NICE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content_weibo}}';
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
            [['user_id'], 'string', 'max' => 32],
            [['content', 'pic', 'keyword', 'person_mark'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentid' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'content' => Yii::t('app', '内容'),
            'pic' => Yii::t('app', '图片'),
            'gender' => Yii::t('app', '性别'),
            'keyword' => Yii::t('app', '关键词'),
            'status' => Yii::t('app', '状态'),
            'date' => Yii::t('app', '创建时间'),
            'person_mark' => Yii::t('app', '发布者个性标签'),
        ];
    }
}

<?php

namespace common\models\wechatdb;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $cityid
 * @property string $cityname
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
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
            [['cityname'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cityid' => Yii::t('app', 'ID'),
            'cityname' => Yii::t('app', '城市名'),
        ];
    }
}

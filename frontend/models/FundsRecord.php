<?php

namespace frontend\models;

use Yii;

class FundsRecord extends \common\models\FundsRecord
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_name' => Yii::t('app', '消费类型'),
            'type' => Yii::t('app', '记录类别'),
            'status' => Yii::t('app', '记录状态'),
            'funds_record_id' => Yii::t('app', '记录号'),
            'current_balance' => Yii::t('app', '当前账户余额(元)'),
            'record_source' => Yii::t('app', '记录来源'),
            'funds_num' => Yii::t('app', '金额(元)'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
}
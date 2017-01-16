<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;

class WeChatFriend extends \common\models\wechatdb\WechatFriend
{
    /**
     *
     */
    public static function getNumFriends($id)
    {
        $phone = self::findOne($id)->phone;
        if (!$phone) {
            throw new Exception('Not Found Pages');
        }
        $friendModels = WeChatFriend::find()->where([])->asArray()->all();
    }
}
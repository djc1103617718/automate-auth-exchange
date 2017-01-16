<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;

class PicRenren extends \common\models\wechatdb\PicRenren
{
    public static function checkList($contentIdList, $status = self::STATUS_CHECKED_SUCCESS)
    {
        if (!in_array($status, [self::STATUS_CHECKED_SUCCESS, self::STATUS_CHECKED_FAILURE])) {
            throw new Exception('param status error!');
        }
        $transaction = Yii::$app->weChatDb->beginTransaction();
        try {
            if (self::updateAll(['status' => $status], ['picid' => $contentIdList])) {
                $transaction->commit();
                return true;
            }
            throw new Exception('没有值受到影响!');
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public static function statusDropDownList()
    {
        return [
            self::STATUS_UNCHECKED => '未审核',
            self::STATUS_CHECKED_SUCCESS => '通过审核',
            self::STATUS_CHECKED_FAILURE => '废弃'
        ];
    }
}
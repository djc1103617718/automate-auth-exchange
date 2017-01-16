<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;

class ContentWeibo extends \common\models\wechatdb\ContentWeibo
{
    public static function statusDropDownList()
    {
        return [
            self::STATUS_DELETE => '废弃',
            self::STATUS_NORMAL => '未审核',
            self::STATUS_NICE => '收录',
        ];
    }

    /**
     * @param array $contentIdList
     * @param int $status
     * @return bool
     * @throws Exception
     */
    public static function checkList($contentIdList, $status = self::STATUS_NICE)
    {
        if (!in_array($status, [self::STATUS_NICE, self::STATUS_DELETE])) {
            throw new Exception('param status error!');
        }
        $transaction = Yii::$app->weChatDb->beginTransaction();
        try {
            if (self::updateAll(['status' => $status], ['contentid' => $contentIdList])) {
                $transaction->commit();
                return true;
            }
            throw new Exception('没有值受到影响!');
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
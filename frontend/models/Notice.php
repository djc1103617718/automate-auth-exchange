<?php

namespace frontend\models;

class Notice extends \common\models\Notice
{
    public static function remarkAlready($noticeIds)
    {
        Notice::updateAll(['status' => self::STATUS_ALREADY_READ],['notice_id' => $noticeIds]);
    }
}
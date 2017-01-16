<?php

namespace common\helper;

use Yii;

class IdBuilder {

    public static $errorMsg;

    /**
     * @return bool|string
     */
    public static function getUniqueId(){

        $user_id = Yii::$app->user->id;
        if (!$user_id) {
            static::$errorMsg = '非法用户';
            return false;
        }
        $r1 = rand(100,999);
        $r2 = rand(1000,9999);
        $time = date('YmdHis', time());
        $id = $time . $r1 . ($user_id+17) . $r2;
        return $id;
    }
}
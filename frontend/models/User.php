<?php

namespace frontend\models;

class User extends \common\models\User
{
    /**
     * @param $password
     * @return string
     */
    public static function setUserPassword($password)
    {
        return \Yii::$app->security->generatePasswordHash($password);
    }
}


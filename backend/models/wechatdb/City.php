<?php

namespace backend\models\wechatdb;

class City extends \common\models\wechatdb\City
{
    public static function cityList()
    {
        return City::find()->select('cityname')->asArray()->indexBy('cityid')->column();
    }
}
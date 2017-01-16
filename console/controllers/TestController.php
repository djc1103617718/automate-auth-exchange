<?php

namespace console\controllers;

use Yii;
use backend\models\wechatdb\City;
use backend\models\wechatdb\Device;
use backend\models\wechatdb\DeviceSearch;
use backend\models\wechatdb\WeChatSearch;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionTest()
    {
       var_dump(City::cityList());
    }
}
<?php

namespace console\controllers;

use console\models\Vpn;
use Yii;
use yii\console\Controller;

class VpnRepeatLogController extends Controller
{
    public function actionCreate()
    {
        Vpn::statisticRepetition();
    }
}

<?php

namespace  console\controllers;

use common\helper\Time;
use backend\models\wechatdb\RegisterDailyStatistics;
use yii\console\Controller;

class RegisterDailyStatisticsController extends Controller
{
    public function actionStatistics360($startTime = null, $endTime = null)
    {
        if ($startTime === null) {
            $startTime = date('Y-m-d H:i:s', Time::getTheDayBeforeStartTime());
        }
        if ($endTime === null) {
            $endTime = date('Y-m-d H:i:s', Time::getTheDayBeforeEndTime());
        }
        if (!RegisterDailyStatistics::dailyStatisticsFor360($startTime, $endTime)) {
            echo '统计失败';
        }
    }

    public function actionStatisticsBizhi($startTime = null, $endTime = null)
    {
        if ($startTime === null) {
            $startTime = date('Y-m-d H:i:s', Time::getTheDayBeforeStartTime());
        }
        if ($endTime === null) {
            $endTime = date('Y-m-d H:i:s', Time::getTheDayBeforeEndTime());
        }
        if (!RegisterDailyStatistics::dailyStatisticsForBizhi($startTime, $endTime)) {
            echo '统计失败';
        }
    }
}

<?php
namespace console\controllers;

use console\models\AccountJobLog;
use yii\console\Controller;

class AccountJobLogController extends Controller
{
    /**
     * 执行定时脚本每日定时统计每个账号完成的任务
     * 当前只做了微信账号的统计
     */
    public function actionCreate()
    {
        AccountJobLog::execute();
    }
}
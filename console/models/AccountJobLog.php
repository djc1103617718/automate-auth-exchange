<?php

namespace console\models;

use Yii;
use common\mail\models\Mail;
use common\models\Job;
use common\helper\Time;
use yii\base\Exception;

class AccountJobLog extends \common\models\wechatdb\AccountJobLog
{
    /**
     * 批量插入账号任务记录
     */
    public static function execute()
    {
        $transaction = Yii::$app->weChatDb->beginTransaction();
        try {
            $accounts = static::getAllAccount();
            $insertData = static::getAllInsertData($accounts);
            if (!$insertData) {
                print_r('no data');
                return ;
            }
            $affect = Yii::$app
                ->weChatDb
                ->createCommand()
                ->batchInsert('account_job_log', ['account', 'app_name', 'job_id', 'job_num', 'commission', 'statistics_time', 'created_time', 'updated_time'], $insertData)
                ->execute();
            if (!$affect) {
                throw new Exception('保存失败');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            $mail = new Mail();
            $mail->accountJobLogError();
            print_r('time:'. date('Y-m-d H:i:s', time()) . '\n\r' . $e->getMessage());
        }
    }

    /**
     * 返回所有账号
     * @return array|\yii\db\ActiveRecord[]
     * @throws Exception
     */
    public static function getAllAccount()
    {
        $weChatAccount = WeChat::find()
            ->select(['phone'])
            ->where(['status' => WeChat::STATUS_NORMAL])
            ->asArray()
            ->all();
        if (!$weChatAccount) {
            throw new Exception('没有微信账号');
        }
        return array_values($weChatAccount);
    }

    /**
     * 返回所有要插入的数据行
     * @param $accounts
     * @return array
     */
    public static function getAllInsertData($accounts)
    {
        $all = [];
        foreach ($accounts as $key => $account) {
            $results = static::getDeviceLogByAccount($account);
            if ($results){
                $all = array_merge($all, static::getInsertData($results));
            }
        }
        return $all;
    }

    /**
     * 通过账号找到该账号下的所有job记录
     * @param $account
     * @return null|array
     */
    protected static function getDeviceLogByAccount($account)
    {
        $accountNumber = $account['phone'];
        unset($account);
        $finalYes = DeviceLog::FINAL_YES;
        $theDayBeforeStart = date('Y-m-d H:i:s', Time::getTheDayBeforeStartTime());
        $theDayBeforeEnd = date('Y-m-d H:i:s', Time::getTheDayBeforeEndTime());
        $results = Yii::$app
            ->weChatDb
            ->createCommand("SELECT account,app_name,jobid,count(*) as job_num FROM devicelog WHERE account = '$accountNumber' AND final = $finalYes AND log_time >= '$theDayBeforeStart' AND log_time < '$theDayBeforeEnd' GROUP BY jobid")
            ->queryAll();
        if (!$results) {
            return null;
        }
        return $results;
    }

    /**
     * 单个账号的所有插入的数据行
     * @param array|null $results
     * @return null|array
     */
    protected static function getInsertData($results)
    {
        if (!$results) {
            return null;
        }
        $statistics_time = Time::getTheDayBeforeEndTime();
        $now = time();
        foreach ($results as $key => $result) {
            $commission = (Job::findOne($result['jobid'])->price) * $result['job_num'];
            $results[$key]['commission'] = $commission;
            $results[$key]['statistics_time'] = $statistics_time;
            $results[$key]['created_time'] = $now;
            $results[$key]['updated_time'] = $now;
        }
        return $results;
    }
}
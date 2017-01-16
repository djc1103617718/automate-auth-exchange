<?php

namespace console\controllers;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use common\models\wechatdb\Device;
use console\models\WeChat;
use common\helper\Time;
use console\models\DeviceLog;

class WeChatOnlineTimeController extends Controller
{
    const APP_NAME_WECHAT = 'com.tencent.mm';

    /**
     * 定时执行脚本统计当天所有设备下的微信号的在线时长
     */
    public function actionCreate()
    {
        $dayBeforeEnd = Time::getTheDayBeforeEndTime();
        //所有的设备
        $devices = static::getAllDevice();
        if (empty($devices)) {
            return;
        }

        $insertList = [];
        //循环设备
        foreach ($devices as $device_id) {
            //该设备下的所有微信账号
            $accouts = static::getAllWeChatByDeviceID($device_id);
            if (empty($accouts)) {
                continue;
            }
            //该设备下所有的微信账号执行日志记录
            $deviceLogs = static::deviceLogForWeChat($device_id);

            //循环该设备下的微信账号
            foreach ($accouts as $accout) {
                if (empty($accout['account'])) {
                    $cur = $accout['phone'];
                } elseif($accout['phone']) {
                    $cur = $accout['account'];
                } else {
                    throw new Exception('数据有误!该记录缺乏微信帐号字段记录');
                }

                //循环该设备下所有执行日志计算该微信账号的执行时长
                $time = 0;
                $preAccount = null;
                $currentAccount = null;
                if (empty($deviceLogs)) {
                    $insertList[] = [$device_id, $cur, $time, $dayBeforeEnd ,time()];
                    continue;
                }
                foreach ($deviceLogs as $log) {
                    if (!$preAccount) {
                        $preAccount = $log;
                        continue;
                    }
                    if ($log['account'] == $cur) {
                        $currentAccount = $log;
                        $time += ($currentAccount['log_time'] - $preAccount['log_time']);
                        $preAccount = $currentAccount;
                    } else {
                        $preAccount = $log;
                    }
                }

                $insertList[] = [$device_id, $cur, $time, $dayBeforeEnd ,time()];
            }
        }
        // 批量将微信账号的在线时长记录插入数据库
        $transaction = Yii::$app->weChatDb->beginTransaction();
        try {
            static::insertOnlineTimeLog($insertList);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo '插入数据失败!' . $e->getMessage();
        }

    }

    /**
     * @return array
     */
    public static function getAllDevice()
    {
        return Device::find()->select(['deviceid'])->asArray()->column();
    }

    /**
     * 获取指定设备下的所有的微信账号
     *
     * @param $device_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllWeChatByDeviceID($device_id)
    {
        $weChat = WeChat::find()
            ->select(['wechatid', 'account', 'phone'])
            ->where(['status' => WeChat::STATUS_NORMAL, 'deviceid' => $device_id])
            ->asArray()
            ->all();
        return $weChat;
    }

    /**
     * 获取指定设备下的所有微信账号的执行记录
     *
     * @param $device_id
     * @return mixed
     */
    public static function deviceLogForWeChat($device_id)
    {
        $app_name = self::APP_NAME_WECHAT;
        $dayBeforeStart = date('Y-m-d H:i:s', Time::getTheDayBeforeStartTime());
        $dayBeforeEnd = date('Y-m-d H:i:s', Time::getTheDayBeforeEndTime());
        $logs = Yii::$app->weChatDb
            ->createCommand("SELECT account, UNIX_TIMESTAMP(log_time) AS log_time FROM `devicelog` WHERE deviceid=:deviceid AND app_name='$app_name' AND log_time BETWEEN '$dayBeforeStart' AND '$dayBeforeEnd' ORDER BY log_time ASC")
            ->bindValue(':deviceid', $device_id)
            ->queryAll();
        return $logs;
    }

    /**
     * 批量将微信账号的在线时长记录插入数据库
     * @param array $insertList
     */
    public static function insertOnlineTimeLog($insertList)
    {
        Yii::$app->weChatDb->createCommand()
            ->batchInsert('wechat_online_time_log', ['device_id', 'account', 'online_time', 'statistics_time', 'created_time'], $insertList)
            ->execute();
    }

}
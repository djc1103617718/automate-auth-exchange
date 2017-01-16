<?php

namespace console\models;

use Yii;
use common\models\Notice;
use common\helper\Time;
use yii\base\Exception;

class Vpn extends \backend\models\wechatdb\Vpn
{
    /**
     * 定时统计昨天重复率
     */
    public static function statisticRepetition()
    {
        $transaction = Yii::$app->weChatDb->beginTransaction();
        try {
            $insertData = static::getInsertData();
            if (empty($insertData)) {
                static::sendNotice();
                return;
            }
            // 检查是否有VPN重复率过高,并发通知给服务端
            static::sendNotice($insertData);

            Yii::$app->weChatDb->createCommand()
                ->batchInsert('vpn_repeat_log', ['vpn_id', 'vpn_name', 'repetition_rate', 'city', 'vpn_ip', 'username', 'password', 'statistics_time', 'created_time'], $insertData)
                ->execute();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private static function getInsertData()
    {
        $start = Time::getTheDayBeforeStartTime();
        $end = Time::getTheDayBeforeEndTime();
        $vpnRateList = static::getVpnRepetitionRate($start, $end);
        $data = [];
        if (empty($vpnRateList)) {
            return $data;
        }
        $vpnList = static::getAllVpn();
        if (empty($vpnList)) {
            throw new Exception('No VPN');
        }
        foreach ($vpnRateList as $vpn_id => $rate) {
            if (empty($vpnList[$vpn_id])) {
                throw new Exception('没有对应的vpn数据');
            }
            $vpn = $vpnList[$vpn_id];
            $data[] = [
                $vpn['vpnid'],
                $vpn['vpnname'],
                $rate,
                $vpn['city'],
                $vpn['vpnip'],
                $vpn['username'],
                $vpn['password'],
                $end,
                time(),
            ];
        }
        return $data;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    private static function getAllVpn()
    {
        return Vpn::find()->indexBy('vpnid')->asArray()->all();
    }

    /**
     * @param $insertData
     */
    protected static function sendNotice($insertData = null)
    {
        if ($insertData === null) {
            $notice = new Notice();
            $notice->category_name = Notice::CATEGORY_NAME_SERVER_NOTICE;
            $notice->title = 'VPN使用异常';
            $notice->description =  date("Y-m-d",time()-86400) . '日没有VPN账号被使用!';
            $notice->content = 'VPN账号使用异常,' . date("Y-m-d",time()-86400) . '日没有VPN账号被使用,请检查VPN的使用情况';
            $notice->save();
            return;
        }
        foreach ($insertData as $item) {
            if ($item[2] >= 0.4) {
                $notice = new Notice();
                $notice->category_name = Notice::CATEGORY_NAME_SERVER_NOTICE;
                $notice->title = 'VPN使用异常';
                $notice->description = 'VPN账号重复率过高';
                $notice->content = 'VPN账号使用异常,请检查VPN的使用情况';
                $notice->save();
                break;
            }
        }
    }

}
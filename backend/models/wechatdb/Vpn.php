<?php

namespace backend\models\wechatdb;

class Vpn extends \common\models\wechatdb\Vpn
{
    public static function vpnList()
    {
        return [
            self::USED_CONDITION_FALSE => '没有使用',
            self::USED_CONDITION_TRUE => '已经使用',
        ];
    }

    /**
     * @param null $start
     * @param null $end
     * @return array|null
     */
    public static function getVpnRepetitionRate($start = null, $end = null)
    {
        $startTime = isset($start)? date("Y-m-d H:i:s", $start) : date('Y-m-d H:i:s', strtotime(date('Y-m-d')));
        $endTime = isset($end)? date('Y-m-d H:i:s', $end) : date('Y-m-d H:i:s', time());
        $vpnLogs = VpnUsage::find()
            ->where(['between', 'access_time', $startTime, $endTime])
            ->select(['vpnid', 'ipaddr'])
            ->asArray()
            ->all();
        if (empty($vpnLogs)) {
            return null;
        }
        $vpnRepeatList = [];
        foreach ($vpnLogs as $item) {
            $vpnId = $item['vpnid'];
            unset($item['vpnid']);
            $vpnRepeatList[$vpnId][] = $item;
        }
        $vpnRepeatList = static::getRepeatAddrToSameVpnId($vpnRepeatList);
        return static::calculateRepeat($vpnRepeatList);
    }

    /**
     * @param $vpnRePeatList
     * @return array
     */
    protected static function getRepeatAddrToSameVpnId($vpnRePeatList)
    {
        $list = [];
        foreach ($vpnRePeatList as $vpn_id => $vpnList) {
            $list[$vpn_id]['allNum'] = count($vpnList);
            foreach ($vpnList as $item) {
                $ip = $item['ipaddr'];
                if (isset($list[$vpn_id][$ip])) {
                    $list[$vpn_id][$ip] += 1;
                } else {
                    $list[$vpn_id][$ip] = 0;
                }
            }
        }
        return $list;
    }

    /**
     * @param $list
     * @return array
     */
    protected static function calculateRepeat($list)
    {
        $vpn_rate = [];
        foreach ($list as $vpn_id => $addr) {
            $repeatNum = 0;
            $allNum = $addr['allNum'];
            unset($addr['allNum']);
            foreach ($addr as $value) {
                $repeatNum += $value;
            }
            $vpn_rate[$vpn_id] = $repeatNum/$allNum;
        }
        return $vpn_rate;
    }
}
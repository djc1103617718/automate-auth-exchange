<?php

namespace common\helper;

class Time
{
    public static function getTheDayBeforeStartTime()
    {
        return strtotime(date('Y-m-d')) - 3600*24;
    }

    public static function getTheDayBeforeEndTime()
    {
        return strtotime(date('Y-m-d'));
    }

    public static function dateFormat($time)
    {
        if (!$time) {
            $time = time();
        }
        return date('Y-m-d H:i:s', $time);
    }
}
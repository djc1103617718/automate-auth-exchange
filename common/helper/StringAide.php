<?php

namespace common\helper;

class StringAide
{
    /**
     * 获取字符串长度
     *
     * @param $str
     * @return int
     */
    public static function strLength($str)
    {
        preg_match_all("/./us", $str, $matches);
        return count(current($matches));
    }

    /**
     * 判断字符串是否为整数
     *
     * @param $value
     * @return bool
     */
    public static function isInteger($value) {
        if (is_numeric($value) && is_int($value+0)) {
            return true;
        }
        return false;
    }
}
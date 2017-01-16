<?php

namespace common\helper;

/**
 * 数组助手
 * Class ArrayAide
 * @package common\helper
 */
class ArrayAide{
    /**
     * 取关联数组的值
     *
     * @param array $array
     * @param int $index 取第几个元素
     * @return string
     */
    public static function getValue($array, $index=0)
    {
        $array = array_values($array);
        return $array[$index];
    }

    /**
     * 多维数组按第二维数组中的某个字段值排序
     *
     * @param $multiArray
     * @param $sortKey
     * @param int $sort
     * @return array|bool
     */
    public static function multiArraySort($multiArray,$sortKey,$sort=SORT_DESC){
        if(is_array($multiArray)){
            $keyArray = [];
            foreach ($multiArray as $rowArray){
                if(is_array($rowArray)){
                    $keyArray[] = $rowArray[$sortKey];
                }else{
                    return false;
                }
            }
            array_multisort($keyArray,$sort,$multiArray);
            return $multiArray;
        }
        return false;
    }

}
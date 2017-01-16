<?php

namespace common\helper;

class RandomCodeGenerator
{
    public static function getCode()
    {
        $pre_str = 'randomCodeGenerator';
        $id = uniqid();
        return md5($pre_str . $id);
    }
}
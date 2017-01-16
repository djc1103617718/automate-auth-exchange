<?php

namespace common\helper\views;

use Yii;
use yii\helpers\Html;

/**
 * 方便detailView插件的使用和优化
 *
 * Class DetailViewHelper
 * @package common\helper
 */
class DetailViewHelper
{
    public static function link($label, $url, $options)
    {
        return Html::a($label, $url, $options);
    }
}


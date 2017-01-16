<?php

namespace frontend\assets;

use yii\base\View;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LoginAndSignUpAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/default.css',
        'css/public.css',
    ];
    public $js = [
        /*'js/jquery.1.7.2.js',
        'js/jquery.validate.js',*/
    ];
    public $jsOptions = [
        'position'=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
    ];
}

<?php

namespace frontend\assets;

use yii\base\View;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'dialog/css/ui-dialog.css',
    ];
    //oksvn
    public $js = [
        'js/dropdown.js',
        'dialog/js/dialog-plus-min.js',
    ];
    public $jsOptions = [
        'position'=>\yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

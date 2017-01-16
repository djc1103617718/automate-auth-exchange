<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '菜单和权限分配';
$this->params['breadcrumbs'][] = $this->title;

echo \backend\widgets\ArrayListWidget::widget( [
    'array'    => $results,
    'pageSize' => 15,
    'itemView' => '/widgets/_item',
    'listView' => '/widgets/_list',
    'width'    => 2,
    'actions'  => [
        [
            'name'   => 'edit',
            'url'    => 'manager/request-edit',
            'params' => [
                'request'
            ]
        ],
        [
            'name'   => 'del',
            'url'    => 'manager/request-del',
            'params' => [
                'request'
            ],
            'alert' => '确定删除？'
        ],
    ]
] );


?>



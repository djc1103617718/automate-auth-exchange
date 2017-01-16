<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '权限列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<a class="btn btn-success" href="?r=manager/auth-add">添加权限</a>

<?php
echo \backend\widgets\ArrayListWidget::widget( [
    'array'    => $results,
    'pageSize' => 15,
    'itemView' => '/widgets/_item',
    'listView' => '/widgets/_list',
    'width'    => 1,
    'actions'  => [
        [
            'name'   => 'edit',
            'url'    => 'manager/auth-edit',
            'params' => [
                'name'
            ]
        ],
        [
            'name'   => 'del',
            'url'    => 'manager/auth-del',
            'params' => [
                'name'
            ],
            'alert' => '确定删除？'
        ],
    ]
] );


?>



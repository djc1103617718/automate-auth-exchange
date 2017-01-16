<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '权限列表';
$this->params['breadcrumbs'][] = $this->title;

?>

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
            'url'    => 'manager/admin-edit',
            'params' => [
                'id'
            ]
        ]
    ]
] );


?>



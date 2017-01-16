<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\NoticeCategory */

$this->title = '分类详情:' . $model->category_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-category-view">
    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('更新', 'btn btn-warning', 'update')->link(['notice-category/update', 'id' => $model->category_id]);
    $btn->buttonDefault('删除', 'btn btn-danger', 'delete')->confirm([
        'url' => ['notice-category/delete', 'id' => $model->category_id],
        //'data' => ['id' => $model->category_id],
        'content' => '一旦删除将无法恢复,你确定要删除吗?'
    ]);
    ButtonGroup::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'category_id',
            'category_name',
            'pid',
            'status',
            [
                'attribute' => 'created_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\Notice */

$this->title = '消息详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有消息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-view">
    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('删除', 'btn btn-default', 'delete')->confirm([
        'url' => ['notice/delete', 'id' => $model->notice_id],
        //'data' => ['id' => $model->notice_id],
    ]);
    ButtonGroup::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'notice_id',
            'user_id',
            'category_name',
            'title',
            'description',
            [
                'attribute' => 'content',
                'format' => 'raw',
            ],
            'status',
            \common\helper\views\ColumnDisplay::dateValue('created_time'),
            \common\helper\views\ColumnDisplay::dateValue('updated_time'),
        ],
    ]) ?>

</div>

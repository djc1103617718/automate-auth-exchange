<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model frontend\models\Notice */

$this->title = '新消息';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有消息'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-view">
    <?php
        $button = ButtonGroup::begin();
        $button->buttonDefault('删除', 'btn btn-default', 'delete')->confirm(['title' => '删除消息','data' => ['id'=>$model->notice_id], 'url' => 'notice/delete']);
        //$button->button('view', 'btn btn-default')->link('###');
        ButtonGroup::end();
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'notice_id',
            //'user_id',
            'category_name',
            'title',
            'description',
            'content:raw',
            'status',
            [
                'attribute' => 'created_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>

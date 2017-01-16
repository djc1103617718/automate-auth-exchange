<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\JobType */

$this->title = 'ID:' . $model->type_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化代号'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-type-view">

    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('更新', 'btn btn-warning', 'update')->link(['job-type/update', 'id' => $model->type_id]);
    $btn->buttonDefault('删除', 'btn btn-danger', 'delete')->confirm([
        'url' => 'job-type/delete',
        'data' => ['id' => $model->type_id],
        'content' => '一旦删除将无法恢复,并且将影响与之关联APP,你确定要删除吗?'
    ]);
    ButtonGroup::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type_id',
            'step_symbol',
            'job_type_name',
            [
                'label' => 'APP名',
                'value' => \yii\helpers\ArrayHelper::getValue($model,function ($model) {
                    return \backend\models\App::findOne($model->app_id)->app_name;
                })
            ],
            [
                'attribute' => 'status',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\JobType::getStatusName($model->status);
                })
            ],
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

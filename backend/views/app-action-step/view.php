<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\AppActionStep */

$this->title = '自动化详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化步骤'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-step-view">
    <?php
    $button = ButtonGroup::begin();
    $button->buttonDefault('增加自动化', 'btn btn-success', 'add')->link(['app-action-step/create', 'id' => $model->action_id]);
    $button->buttonDefault('查看自动化步骤详情', 'btn btn-primary', 'view')->link(['app/detail-index', 'AppSearch[step_id]' => $model->step_id]);
    $button->buttonDefault('更新自动化', 'btn btn-warning', 'update')->link(['app-action-step/update', 'id' => $model->step_id]);
    $button->buttonDefault('删除自动化', 'btn btn-danger', 'delete')->confirm([
        'url' => ['app-action-step/delete', 'id' => $model->step_id],
        'title' => '删除自动化步骤!',
        'content' => '删除该步骤将无法恢复,这将改变步骤对应的任务模版,你确定要删除吗?',
        //'data' => ['id' => $model->step_id],
    ]);
    ButtonGroup::end();
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'step_id',

            [
                'label' => '动作名',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\AppAction::findOne($model->action_id)->action_name;
                })
            ],
            [
                'label' => '自动化代号名',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    $step = \backend\models\JobType::findOne(['step_symbol' => $model->step_symbol]);
                    if ($step) {
                        return $step->job_type_name . '(' .$model->step_symbol . ')';
                    } else {
                        return '(' . $model->step_symbol . ')not set';
                    }
                })
            ],
            'job_param',
            'sort',
            [
                'attribute' => 'status',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\AppActionStep::getStatusName($model->status);
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

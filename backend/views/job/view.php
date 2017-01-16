<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\Job */

$this->title = '任务详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有任务'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-view">
    <?php if ($model->status == $model::STATUS_NEW) {
        $btn = ButtonGroup::begin();
        $btn->button('确认任务', 'btn btn-success', null, 'fa fa-check-square-o')->link(['job/ensure-job', 'id' => $model->job_id]);
        $btn->buttonDefault('取消任务', 'btn btn-danger', 'delete')->confirm([
            'title' => '取消任务',
            'content' => '该任务取消后将不可恢复,你确定取消吗?',
            'url' => ['job/cancel-job', 'id' => $model->job_id],
        ]);
        ButtonGroup::end();
        //echo Html::a('确认任务', ['job/ensure-job', 'id' => $model->job_id], ['class' => 'btn btn-primary']);
        //echo '&nbsp;' . Html::a('取消任务', ['job/cancel-job', 'id' => $model->job_id], ['class' => 'btn btn-danger']);
    }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'job_id',
            'job_name',
            'user.username',
            'price_rate',
            'price_introduction',
            [
                'attribute' => 'price',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return $model->price/100;
                })
            ],
            'num',
            [
                'label' => '任务总费用(元)',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function($model) {
                    return $model->num . 'x' . ($model->price/100) . '=' . $model->num * ($model->price/100);
                })
            ],
            'finished',
            \common\helper\views\ColumnDisplay::dateValue('ensure_time'),
            [
                'attribute' => 'status',
                'value' =>  \yii\helpers\ArrayHelper::getValue(\backend\models\Job::statusArray(), $model->status),
            ],
            \common\helper\views\ColumnDisplay::dateValue('created_time')

        ],
    ]) ?>

</div>

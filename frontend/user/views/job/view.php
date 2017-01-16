<?php

use yii\helpers\Html;
use \common\helper\views\ButtonGroup;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Job */

if ($model->status == \frontend\models\Job::STATUS_DRAFT) {
    $this->title = '草稿详情';
}else {
    $this->title = '任务详情';
}

$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-view">

    <div style="clear: left">
        <?php
        if ($model->status == \frontend\models\Job::STATUS_DRAFT) {
            $btn = ButtonGroup::begin();
            $btn->button('提交为新任务', 'btn btn-success', null)->link(['task-template/draft-to-task', 'id' => $model->job_id]);
            $btn->buttonDefault('更新草稿', 'btn btn-primary', 'update')->link(['task-template/draft-to-task', 'id' => $model->job_id]);
            $btn->buttonDefault('删除草稿', 'btn btn-warning', 'delete')->confirm([
               'url' =>  ['job/delete', 'id' => $model->job_id],
            ]);
            ButtonGroup::end();
            /*echo Html::a('提交为新任务', ['task-template/draft-to-task', 'id' => $model->job_id], ['class' => 'btn btn-default']);
            echo '&nbsp;' . Html::a('更新草稿', ['task-template/draft-update', 'id' => $model->job_id], ['class' => 'btn btn-default']);
            echo '&nbsp;' . Html::a('删除草稿', ['delete', 'id' => $model->job_id], [
            'class' => 'btn btn-default',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
            ]);*/
        }?>
    </div>

    <table id="w0" class="table table-striped table-bordered detail-view">
        <tr><th>任务编号</th><td><?=$model->job_id ?></td></tr>
        <tr><th>任务名称</th><td><?=$model->job_name ?></td></tr>
        <?php
            foreach ($jobParams as $jobParam) {
                echo "<tr><th>$jobParam->input_box_name</th><td>$jobParam->value</td></tr>";
            }
        ?>
        <tr><th>任务单价(元)</th><td><?=($model->price/100) ?></td></tr>
        <tr><th>任务量</th><td><?=$model->num ?></td></tr>
        <tr><th>任务总费用(元)</th><td><?=$model->num . 'x' . ($model->price/100) . '=' . $model->num * ($model->price/100)?></td></tr>
        <tr><th>任务速度</th><td><?=$model->price_rate ?></td></tr>
        <tr><th>价格简介</th><td><?=$model->price_introduction ?></td></tr>
        <tr><th>已完成量</th><td><?=$model->finished ?></td></tr>
        <tr><th>过期时间</th><td>
        <?php
            if ($model->expire_time) {
                echo date('Y-m-d H:i:s', $model->expire_time);
            } else {
                echo '未设置';
            }

        ?></td></tr>
        <tr><th>审核时间</th><td>
        <?php
            if ($model->ensure_time) {
                echo date('Y-m-d H:i:s', $model->ensure_time);
            } else {
                echo '未设置';
            }
        ?>
        </td></tr>
        <tr><th>任务状态</th><td><?= \yii\helpers\ArrayHelper::getValue(\frontend\models\Job::statusArray(), $model->status)?></td></tr>
        <tr><th>备注</th><td><?=$model->job_remark ?></td></tr>
        <tr><th>创建时间</th><td><?= date('Y-m-d H:i:s', $model->created_time) ?></td></tr>
        <tr><th>更新时间</th><td><?= date('Y-m-d H:i:s', $model->updated_time) ?></td></tr>
    </table>

</div>

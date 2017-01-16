<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\DeviceLog */

$this->title = $model->logid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设备任务日志'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-log-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'logid',
            'deviceid',
            'app_name',
            'account',
            'log_time',
            'job_type',
            'jobid',
            'status',
            'params',
            'final',
        ],
    ]) ?>

</div>

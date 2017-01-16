<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\AccountJobLog */

$this->title = '任务日志详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '账号任务日志'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-job-log-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'job_log_id',
            'account',
            'app_name',
            'job_id',
            'job_num',
            'commission',
            [
                'attribute' => 'statistics_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
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

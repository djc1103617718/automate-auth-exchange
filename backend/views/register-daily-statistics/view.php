<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\wechatdb\DailyStatisticsFor360;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\RegisterDailyStatistics */

$this->title = '详情:' . $model->log_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $title), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-statistics-for360-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'log_id',
            'register_num',
            'login_num',
            'statistics_time',
            'created_time',
        ],
    ]) ?>

</div>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\WechatOnlineTimeLog */

$this->title = 'ID:' . $model->log_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '微信在线时长日志'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wechat-online-time-log-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'log_id',
            'account',
            'device_id',
            [
                'attribute' => 'online_time',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return $model->online_time/60 . '分钟';
                })
            ],
            ColumnDisplay::dateValue('statistics_time'),
            ColumnDisplay::dateValue('created_time')
        ],
    ]) ?>

</div>

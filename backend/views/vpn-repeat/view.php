<?php

use common\helper\views\ColumnDisplay;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatDb\VpnRepeatLog */

$this->title = '记录详情:'.$model->log_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VPN重复率日志'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-repeat-log-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'log_id',
            'vpn_name',
            'vpn_id',
            'vpn_ip',
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                })
            ],
            'username',
            'password',
            [
                'attribute' => 'repetition_rate',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    $rate = isset($model->repetition_rate) ? $model->repetition_rate*100 . '%':'未使用';
                    return $rate;
                })
            ],
            /*[
                'attribute' => 'used',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return ColumnDisplay::displayStatus($model->used, [
                        \backend\models\wechatdb\Vpn::USED_CONDITION_TRUE => ['已使用', 'primary'],
                        \backend\models\wechatdb\Vpn::USED_CONDITION_FALSE => ['未使用', 'warning']
                    ]);
                })
            ],*/
            ColumnDisplay::dateValue('statistics_time'),
            ColumnDisplay::dateValue('created_time'),
        ],
    ]) ?>

</div>

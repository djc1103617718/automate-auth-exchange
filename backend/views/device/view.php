<?php

use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Device */

$this->title = '设备详情:' . $model->nouseid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设备列表'), 'url' => ['device-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-view">

    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('更新', 'btn btn-warning', 'update')->link(['update-vpn', 'id' => $model->nouseid]);
    $btn::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nouseid',
            'deviceid',
            'last_connect_time',
            'province',
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                })
            ],
            'last_job_type',
            'last_job_param',
            'account',
            'wechat',
            'localip',
            'appleid',
            'vpnid',
        ],
    ]) ?>

</div>

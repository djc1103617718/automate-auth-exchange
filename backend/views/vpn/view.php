<?php

use yii\widgets\DetailView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Vpn */

$this->title = 'ID:'.$model->vpnid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VPN列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vpnid',
            'vpnname',
            [
                'label' => '今日重复率',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) use($vpnRate) {
                    $rate = isset($vpnRate[$model->vpnid]) ? $vpnRate[$model->vpnid]*100 . '%':'未使用';
                    if ($rate === '未使用') {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-info');
                    } elseif (isset($vpnRate[$model->vpnid]) && ($vpnRate[$model->vpnid] > 0.4)) {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-danger');
                    } else {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-primary');
                    }
                    return $rate;
                })
            ],
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                })
            ],
            'vpnip',
            'username',
            'password',
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
        ],
    ]) ?>

</div>

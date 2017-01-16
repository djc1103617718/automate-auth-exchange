<?php

use common\helper\views\ColumnDisplay;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\VpnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'VPN列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\VpnSearch::searchAttributes());
    $searchWidget->setSearchModelName('VpnSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['vpn/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{view}{update}',
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'vpnid',
            'vpnname',
            [
                'label' => '今日重复率',
                'format' => 'raw',
                'value' => function ($model) use($vpnRate) {
                    $rate = isset($vpnRate[$model->vpnid]) ? $vpnRate[$model->vpnid]*100 . '%':'未使用';
                    if ($rate === '未使用') {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-info');
                    } elseif (isset($vpnRate[$model->vpnid]) && ($vpnRate[$model->vpnid] > 0.4)) {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-danger');
                    } else {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-primary');
                    }
                    return $rate;
                }

            ],
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                }
            ],
            'vpnip',
            //'username',
            //'password',
            /*[
                'attribute' => 'used',
                'format' => 'raw',
                'value' => function ($model) {
                    return ColumnDisplay::displayStatus($model->used, [
                        \backend\models\wechatdb\Vpn::USED_CONDITION_TRUE => ['已使用', 'primary'],
                        \backend\models\wechatdb\Vpn::USED_CONDITION_FALSE => ['未使用', 'warning']
                    ]);
                }
            ],*/
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\VpnUsageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'VPN使用记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-usage-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\VpnUsageSearch::searchAttributes());
    $searchWidget->setSearchModelName('VpnUsageSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['vpn-usage/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'nouseid',
            'deviceid',
            'ipaddr',
            'access_time',
            'vpnid',
        ],
    ]); ?>
</div>

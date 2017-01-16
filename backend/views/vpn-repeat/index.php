<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatDb\VpnRepeatLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'VPN重复率日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-repeat-log-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\VpnRepeatLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('VpnRepeatLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['vpn-repeat/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'log_id',
            'vpn_name',
            [
                'format' => 'raw',
                'attribute' => 'repetition_rate',
                'value' => function ($model) {
                    $rate = isset($model->repetition_rate) ? $model->repetition_rate*100 . '%':'未使用';
                    if ($model->repetition_rate >= 0.4) {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-danger');
                    } elseif ($model->repetition_rate === '未使用') {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-info');
                    } else {
                        $rate = ColumnDisplay::statusLabel($rate, 'label label-primary');
                    }
                    return $rate;
                }
            ],
            'vpn_id',
            'vpn_ip',
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                }
            ],
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
            ColumnDisplay::dateValue('statistics_time'),
            ColumnDisplay::dateValue('created_time'),
        ],
    ]); ?>
</div>

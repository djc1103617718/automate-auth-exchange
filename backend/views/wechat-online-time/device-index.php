<?php

use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\wechatdb\WechatOnlineTimeLogSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\WechatOnlineTimeLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '该设备下微信在线时长');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wechat-online-time-log-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(WechatOnlineTimeLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('WechatOnlineTimeLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['wechat-online-time/device-index', 'id' => $id]));
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

            'log_id',
            'account',
            'device_id',
            [
                'attribute' => 'online_time',
                'value' => function ($model) {
                    return $model->online_time/60 . '分钟';
                }
            ],
            ColumnDisplay::dateValue('statistics_time'),
            ColumnDisplay::dateValue('created_time'),
        ],
    ]); ?>
</div>

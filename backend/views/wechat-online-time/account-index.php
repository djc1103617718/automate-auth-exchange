<?php

use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\wechatdb\WechatOnlineTimeLogSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\WechatOnlineTimeLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '微信在线时长日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wechat-online-time-log-index">

    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(WechatOnlineTimeLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('WechatOnlineTimeLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['wechat-online-time/account-index', 'account' => $account]));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            /*[
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) use ($account) {
                        return  \yii\helpers\Html::a('<span class="fa fa-eye">在线时长</span>', \yii\helpers\Url::to(['wechat-online-time/account-index', 'account' => $account]) , ['title' => '在线时长'] ) ;
                    }
                ]
            ],*/
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

<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use \common\helper\views\ColumnDisplay;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '设备下的微信');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\DeviceSearch::searchAttributes());
    $searchWidget->setSearchModelName('DeviceSearch');
    $searchWidget->setSearchColor('gray');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['device/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{device-wechat-view} {wechat-job-log} {wechat-maintain-log} {wechat-online-time}',
                'buttons' => [
                    'device-wechat-view' => function ($url, $model, $key) {
                        return  Html::a('<span class="fa fa-weixin"></span>', Url::to(['device/device-wechat-view', 'id' => $model['nouseid']]) , ['title' => '设备下微信账号'] ) ;
                    },
                    'wechat-job-log' => function ($url, $model, $key) {
                        return  Html::a('<span class="fa fa-bookmark"></span>', Url::to(['device/wechat-job-log', 'id' => $model['nouseid']]) , ['title' => '微信任务日志'] ) ;
                    },
                    'wechat-maintain-log' => function ($url, $model, $key) {
                        return  Html::a('<span class="fa fa-tree"></span>', Url::to(['device/wechat-maintain-log', 'id' => $model['nouseid']]) , ['title' => '微信养号日志'] ) ;
                    },
                    'wechat-online-time' => function ($url, $model, $key) {
                        return  Html::a('<span class="fa fa-calendar-check-o"></span>', Url::to(['wechat-online-time/device-index', 'id' => $model['nouseid']]) , ['title' => '微信在线时长'] ) ;
                    }
                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //'nouseid',
            [
                'label' => '设备ID',
                'attribute' => 'deviceid',
            ],
            [
                'label' => '账号数量',
                'attribute' => 'num',
            ],
            [
                'label' => '设备状态',
                'format' => 'raw',
                'value' => function($model){
                    $runTime = strtotime($model['last_connect_time'])+600;
                    if (time() <= $runTime) {
                        return ColumnDisplay::statusLabel('运行中', 'label label-success');
                    } else {
                        return ColumnDisplay::statusLabel('休眠中', 'label label-warning');
                    }
                }
            ],
            [
                'label' => '最后运行时间',
                'attribute' => 'last_connect_time',
            ],
            [
                'label' => '运行状态',
                'value' => function ($model) {
                    $job_type =\backend\models\JobType::find()
                        ->select('job_type_name')
                        ->where(['status' => \backend\models\JobType::STATUS_NORMAL, 'step_symbol' => $model['last_job_type']])
                        ->one();
                    if ($job_type) {
                        return $job_type->job_type_name;
                    } else {
                        return '未设置job_type的关联';
                    }
                }
            ],
            // 'last_job_param',
            //'account',
            [
                'label' => '运行微信账号',
                'attribute' => 'wechat',
            ],
        ],
    ]); ?>
</div>

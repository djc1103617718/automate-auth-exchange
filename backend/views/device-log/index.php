<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\DeviceLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '设备任务日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-log-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\DeviceLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('DeviceLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{view}'
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'logid',
            'deviceid',
            'jobid',
            'app_name',
            'account',
            'log_time',
            // 'job_type',
            // 'status',
            // 'params',
            // 'final',
        ],
    ]); ?>
</div>

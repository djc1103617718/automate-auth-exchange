<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\WeChatManageLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '微信养号日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<p>设备ID:<?=$device->deviceid?></p>
<div class="we-chat-manage-log-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\WeChatManageLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('WeChatManageLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['device/wechat-maintain-log', 'id' => $device->nouseid]));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'logid',
            'deviceid',
            'account',
            'log_time',
            [
                'attribute' => 'job_type',
                'label' => '自动化名称',
                'value' => function ($model) {
                    $jobType = \backend\models\JobType::findOne(['step_symbol' => $model->job_type]);
                    if ($jobType) {
                        return $jobType->job_type_name;
                    } else {
                        return '未设置自动化代号的关联';
                    }
                }
            ],
            // 'jobid',
            //'status',
            // 'params',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

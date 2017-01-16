<?php

use yii\helpers\Html;
use common\components\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\AccountJobLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '账号任务日志');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-job-log-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\AccountJobLogSearch::searchAttributes());
    $searchWidget->setSearchModelName('AccountJobLogSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['account-job-log/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\components\grid\ActionColumn',
                'template' => '{view}',
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'job_log_id',
            'account',
            'app_name',
            'job_id',
            'job_num',
            'commission',
            [
                'attribute' => 'statistics_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'created_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]); ?>
</div>

<?php

use yii\grid\GridView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\RegisterDailyStatisticsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '账号每日统计');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-statistics-for360-index">

    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\RegisterDailyStatisticsSearch::searchAttributes());
    $searchWidget->setSearchModelName('RegisterDailyStatisticsSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['index']));
    $searchWidget::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{view}'
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'log_id',
            'register_num',
            'login_num',
            'statistics_time',
            'app_name',
            [
                'label' => '统计日期',
                'value' => function ($model) {
                    $time = strtotime($model->statistics_time) - 24*60*60;
                    return date('Y-m-d', $time);
                }
            ],
            'created_time',
        ],
    ]); ?>
</div>

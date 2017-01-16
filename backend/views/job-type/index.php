<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\JobType;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JobTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '自动化代号');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-type-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\JobTypeSearch::searchAttributes());
    $searchWidget->setSearchModelName('JobTypeSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['job-type/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            'type_id',
            'step_symbol',
            [
                'label' => 'APP名',
                'value' => function ($model) {
                    $app = \backend\models\App::findOne($model->app_id);
                    if ($app) {
                        return $app->app_name;
                    } else {
                        return 'error';
                    }
                }
            ],
            'job_type_name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = JobType::getStatusName($model->status);
                    if ($model->status == JobType::STATUS_NORMAL) {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle(ColumnDisplay::LABEL_STYLE_SUCCESS));
                    } elseif ($model->status == JobType::STATUS_DELETE) {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle(ColumnDisplay::LABEL_STYLE_DEFAULT));
                    } else {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle(ColumnDisplay::LABEL_STYLE_DANGER));
                    }
                }
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

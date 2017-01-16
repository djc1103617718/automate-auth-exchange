<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

new \yii\web\View();
$this->title = Yii::t('app', 'APP列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-index">
    <?php
    $button = ButtonGroup::begin();
    $button->buttonDefault('添加APP', 'btn btn-success', 'add')->link('app/create');
    $button->button('APP列表', 'btn btn-primary')->link('app/index');
    $button->button('APP动作列表', 'btn btn-info')->link('app-action/index');
    $button->button('自动化步骤列表', 'btn btn-primary')->link('app-action-step/index');
    $button->button('自动化步骤详情列表', 'btn btn-info')->link('app/detail-index');
    ButtonGroup::end();
    ?>

    <div style="overflow-x: auto">

        <?php
            $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
            $searchWidget->setDropdownlistAttributes(\backend\models\AppSearch::searchAttributes());
            $searchWidget->setSearchModelName('AppSearch');
            $searchWidget->setSearchColor('gray');
            $searchWidget->setSearchBoxLength(4);
            $searchWidget->setRequestUrl(\yii\helpers\Url::to(['app/index']));
            $searchWidget::end();
        ?>

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],

            'app_id',
            'app_name',
            'package_name',
            'search_name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == \backend\models\App::STATUS_NORMAL) {
                        return ColumnDisplay::statusLabel('正常', ColumnDisplay::getStatusLabelStyle('success'));
                    } elseif ($model->status == \backend\models\App::STATUS_LOCKING) {
                        return ColumnDisplay::statusLabel('锁定', ColumnDisplay::getStatusLabelStyle('warning'));
                    } else {
                        return ColumnDisplay::statusLabel('error', ColumnDisplay::getStatusLabelStyle('danger'));
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
</div>




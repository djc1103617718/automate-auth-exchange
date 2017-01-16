<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\AppActionStep;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AppActionStepsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '自动化步骤');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-step-index">
    <?php
    $button = ButtonGroup::begin();
    $button->button('APP列表', 'btn btn-primary')->link('app/index');
    $button->button('APP动作列表', 'btn btn-info')->link('app-action/index');
    $button->button('自动化步骤列表', 'btn btn-primary')->link('app-action-step/index');
    $button->button('自动化步骤详情列表', 'btn btn-info')->link('app/detail-index');
    ButtonGroup::end();
    ?>
    <div style="overflow-x: auto">
        <?php
        $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
        $searchWidget->setDropdownlistAttributes(\backend\models\AppActionStepSearch::searchAttributes());
        $searchWidget->setSearchModelName('AppActionStepSearch');
        //$searchWidget->setSearchColor('default');
        $searchWidget->setSearchBoxLength(4);
        $searchWidget->setRequestUrl(\yii\helpers\Url::to(['app-action-step/index']));
        $searchWidget::end();
        ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],

            'step_id',
            //'action_id',
            //'step_symbol',
            //'job_param',
            [
                'label' => '动作名',
                'value' => function ($model) {
                    $model = \backend\models\AppAction::findOne($model->action_id);
                    return $model['action_name'];
                }
            ],
            [
                'label' => '自动化代号名',
                'value' => function ($model) {
                    $step = \backend\models\JobType::findOne(['step_symbol' => $model->step_symbol]);
                    if ($step) {
                        return $step['job_type_name'] . '(' .$model->step_symbol . ')';
                    } else {
                        return '(' . $model->step_symbol . ')not set';
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = AppActionStep::getStatusName($model->status);
                    if ($model->status == AppActionStep::STATUS_NORMAL) {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle('success'));
                    } elseif ($model->status == AppActionStep::STATUS_LOCKING) {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle('default'));
                    } else {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle('danger'));
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

<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\AppAction;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AppActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'APP动作');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-index">
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
        $searchWidget->setDropdownlistAttributes(\backend\models\AppActionSearch::searchAttributes());
        $searchWidget->setSearchModelName('AppActionSearch');
        //$searchWidget->setSearchColor('default');
        $searchWidget->setSearchBoxLength(4);
        $searchWidget->setRequestUrl(\yii\helpers\Url::to(['app-action/index']));
        $searchWidget::end();
        ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            'action_id',
            'action_name',
            'app_id',
            'action_class_name',
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return AppAction::getCategoryName($model->category);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = AppAction::getStatusName($model->status);
                    if ($model->status == AppAction::STATUS_NORMAL) {
                        return ColumnDisplay::statusLabel($status, ColumnDisplay::getStatusLabelStyle('success'));
                    } elseif ($model->status == AppAction::STATUS_LOCKING) {
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

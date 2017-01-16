<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ButtonGroup;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '自动化步骤详情列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-index" style="overflow: auto">
    <?php
    $button = ButtonGroup::begin();
    $button->buttonDefault('添加APP', 'btn btn-success', 'add')->link('app/create');
    $button->button('APP列表', 'btn btn-primary')->link('app/index');
    $button->button('APP动作列表', 'btn btn-info')->link('app-action/index');
    $button->button('自动化步骤列表', 'btn btn-primary')->link('app-action-step/index');
    $button->button('自动化步骤详情列表', 'btn btn-info')->link('app/detail-index');
    ButtonGroup::end();
    ?>

    <div>
        <?php
        $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
        $searchWidget->setDropdownlistAttributes(\backend\models\AppSearch::searchAttributes());
        $searchWidget->setSearchModelName('AppSearch');
        //$searchWidget->setSearchColor('default');
        $searchWidget->setSearchBoxLength(4);
        $searchWidget->setRequestUrl(\yii\helpers\Url::to(['app/detail-index']));
        $searchWidget::end();
        ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template' => '{app-action-create} {detail-view} {delete}',
                'buttons' => [
                    'app-action-create' => function ($url, $model, $key) {
                        return  Html::a('<span class="fa fa-plus-circle">添加动作</span>&nbsp;', Url::to(['app-action/create', 'app_id' => $model['app_id']]) , ['title' => '添加动作'] ) ;
                    },
                    'detail-view' => function ($url, $model, $key) {

                        if ($model['app_id'] && $model['action_id'] && $model['step_id']) {
                            return  Html::a('<span class="fa fa-eye">查看</span>', Url::to(['app/detail-view', 'app_id' => $model['app_id'], 'action_id' => $model['action_id'], 'step_id' => $model['step_id']]) , ['title' => '查看整体App'] ) ;
                        } elseif ($model['app_id'] && $model['action_id'] && !$model['step_id']) {
                            return  Html::a('<span class="fa fa-eye">查看</span>', Url::to(['app/detail-view', 'app_id' => $model['app_id'], 'action_id' => $model['action_id']]) , ['title' => '查看整体App'] ) ;
                        } else {
                            return  Html::a('<span class="fa fa-eye">查看</span>', Url::to(['app/detail-view', 'app_id' => $model['app_id']]) , ['title' => '查看整体App'] ) ;
                        }
                    },

                    'delete' => function ($url, $model, $key) {
                        return \common\helper\views\ColumnDisplay::operatingDelete(['url'=>['app/delete', 'id' => $model['app_id']]]);
                    }
                ],
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>'app_id',
                'label' => 'AppID',
            ],
            [
                'attribute' => 'app_name',
                'label' => 'App名',
            ],
            [
                'attribute' => 'package_name',
                'label' => '软件名',
            ],
            [
                'attribute' => 'search_name',
                'label' => '搜索名',
            ],
            /*[
                'label' => 'App Status',
                'value' => function($model){
                    $status = ($model['status'] == 1)? '活跃' : '锁定';
                    return $status;
                }
            ],*/
            [
                'attribute' => 'action_id',
                'label' => '动作ID',
            ],
            [
                'attribute' => 'action_name',
                'label' => '动作名',
            ],
            [
                'attribute' => 'action_class_name',
                'label' => '动作价格类名',
            ],
            [
                'attribute' => 'category',
                'label' => '类型',
            ],
            /*[
                'label' => 'Action Status',
                'value' => function($model){
                    $status = ($model['action_status'] == 1)? '活跃' : '锁定';
                    return $status;
                }
            ],*/
            [
                'attribute' => 'step_id',
                'label' => '自动化ID',
            ],
            [
                'label' => '自动化代号名',
                'value' => function ($model) {
                    $step = \backend\models\JobType::findOne(['step_symbol' => $model['step_symbol']]);
                    if ($step) {
                        return $step->job_type_name . '(' . $model['step_symbol'] . ')';
                    } else {
                        return '(' . $model['step_symbol'] . ')not set';
                    }
                }
            ],
            /*[
                'label' => 'Action Step Status',
                'value' => function($model){
                    $status = ($model['step_status'] == 1)? '活跃' : '锁定';
                    return $status;
                }
            ],*/
        ],
    ]); ?>
    </div>
</div>

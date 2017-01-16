<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $taskTitle;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <!--<div style="overflow-x: auto">
        <div class="row">
            <form name="searchForm" action=''>  method="get" id="searchForm">
                <div class="col-lg-4" style="text-align: right; float: right; margin:25px 2%;">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder='搜索🔍' name="JobSearch[search]" />
                        <input type="hidden" class="form-control"  name="r"  value="user/job/draft-index" />
                        <span class="input-group-btn">
                                <button class="btn btn-default" id= 'searchButton' type="submit">搜索</button>
                            </span>
                    </div>
                </div>
            </form>
        </div>--><!-- /.row -->
    <?php
        $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
        $searchWidget->setDropdownlistAttributes(\frontend\models\JobSearch::searchAttributes());
        $searchWidget->setSearchModelName('JobSearch');
        $searchWidget->setSearchColor('olive');
        $searchWidget->setSearchBoxLength(4);
        $searchWidget->setRequestUrl(\yii\helpers\Url::to(['job/draft-index']));
        $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\components\grid\ActionColumn',
                'header'=>'操作',
                'template' => '{view} {update} {delete}',
                'buttons' =>[
                    'update' => function($url, $model, $key) {
                        return ColumnDisplay::operating('编辑', 'fa fa-pencil-square',Url::to(['task-template/draft-update', 'id' => $model->job_id]));
                    },
                ],

            ],
            ['class' => 'yii\grid\SerialColumn'],

            'job_id',
            'job_name',
            [
                'attribute' => 'price',
                'value' => function($model){
                    return $model->price/100;
                }
            ],
            [
                'attribute' => 'num',
                'headerOptions' => ['width' => '120px'],
            ],
            //'price_rate',
            //'price_introduction',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = \yii\helpers\ArrayHelper::getValue(\frontend\models\Job::statusArray(), $model->status);
                    if ($model->status == \frontend\models\Job::STATUS_DRAFT) {
                        return  ColumnDisplay::statusLabel($status, 'label label-default');
                    } else {
                        return  ColumnDisplay::statusLabel('error', 'label label-danger');
                    }
                }
            ],
            //'job_remark',
            //'finished',
            [
                'attribute' => 'expire_time',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'created_time',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            //'updated_time:datetime',
        ],
    ]); ?>
    </div>
</div>

<?php

use yii\grid\GridView;
use common\helper\views\ColumnDisplay;
use frontend\models\Job;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $taskTitle;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="job-index">
    <div>
        <?php
            $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
            $searchWidget->setDropdownlistAttributes(\frontend\models\JobSearch::searchAttributes());
            $searchWidget->setSearchModelName('JobSearch');
            $searchWidget->setSearchColor('olive');
            $searchWidget->setSearchBoxLength(4);
            $searchWidget->setRequestUrl(\yii\helpers\Url::to(['job/'. $action]));
            $searchWidget::end();
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'common\components\grid\ActionColumn','header'=>'操作','template' => '{view}',],
                ['class' => 'yii\grid\SerialColumn'],
                'job_id',
                'job_name',
                [
                    'attribute' => 'price',
                    'value' => function($model){
                        return $model->price/100;
                    }
                ],
                'num',
                //'price_rate',
                //'price_introduction',
                [
                    'attribute' => 'status',
                    'format' =>'raw',
                    'value' => function ($model) {
                        $status = \yii\helpers\ArrayHelper::getValue(Job::statusArray(), $model->status);
                        if ($model->status == Job::STATUS_AWAITING) {
                            return  ColumnDisplay::statusLabel($status, 'label label-success');
                        } elseif ($model->status == Job::STATUS_EXECUTING) {
                            return  ColumnDisplay::statusLabel($status, 'label label-success');
                        } elseif ($model->status == Job::STATUS_NEW) {
                            return  ColumnDisplay::statusLabel($status, 'label label-primary');
                        } elseif ($model->status == Job::STATUS_DRAFT) {
                            return  ColumnDisplay::statusLabel($status, 'label label-default');
                        } elseif ($model->status == Job::STATUS_CANCEL) {
                            return  ColumnDisplay::statusLabel($status, 'label label-danger');
                        } elseif ($model->status == Job::STATUS_COMPLETE) {
                            return  ColumnDisplay::statusLabel($status, 'label label-default');
                        } else {
                            return  ColumnDisplay::statusLabel('error', 'label label-danger');
                        }
                    }
                ],
                'job_remark',
                //'finished',
                /*[
                    'attribute' => 'expire_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],*/
                [
                    'attribute' => 'created_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],
                /*[
                    'attribute' => 'ensure_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],*/
                /*[
                    'attribute' => 'updated_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],*/
            ],
        ]); ?>
    </div>
</div>
<script type="text/javascript">

</script>

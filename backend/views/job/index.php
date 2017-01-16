<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\Job;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $taskTitle;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-index">
    <?php
        $button = \common\helper\views\ButtonGroup::begin();
        $button->button(Yii::t('app', '新任务'), 'btn btn-danger', null, 'fa fa-exclamation-circle')->link('job/new-index');
        $button->button(Yii::t('app', '待执行任务'), 'btn btn-success', null, 'fa fa-clock-o')->link('job/awaiting-index');
        $button->button(Yii::t('app', '执行中任务'), 'btn btn-primary', null, 'fa fa-spinner')->link('job/executing-index');
        $button->button(Yii::t('app', '已完成任务'), 'btn btn-info', null, 'fa fa-check-circle-o')->link('job/complete-index');
        $button->button(Yii::t('app', '审核失败任务'), 'btn btn-warning', null, 'fa fa-ban')->link('job/cancel-index');
        $button->button(Yii::t('app', '草稿箱任务'), 'btn btn-success')->link('job/draft-index');
        $button->button(Yii::t('app', '任务列表'), 'btn btn-primary', null, 'fa fa-history')->link('job/index');
        $button->button(Yii::t('app', 'Export <span class="fa fa-share-square"></span>'), 'btn btn-success', ['id' => 'job-export']);
        \common\helper\views\ButtonGroup::end();
    ?>
    <div>
        <?php
        $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
        $searchWidget->setDropdownlistAttributes(\backend\models\JobSearch::searchAttributes());
        $searchWidget->setSearchModelName('JobSearch');
        //$searchWidget->setSearchColor('default');
        $searchWidget->setSearchBoxLength(4);
        $searchWidget->setRequestUrl(\yii\helpers\Url::to(['job/' . $action]));
        $searchWidget::end();
        ?>

        <form method="post" action="<?=\yii\helpers\Url::to(['job/export'])?>" id="jobFrom">
        <input type="hidden" name="filename" id="job-filename"/>
        <input type="hidden" name="requestParams" value="<?=$requestParams?>"/>
        <input type="hidden" name="jobStatus" value="<?php if(isset($jobStatus)) echo$jobStatus;?>"/>
        <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'common\components\grid\ActionColumn',
                    //'header' => '操作',
                    'template' => '{view}',
                ],
                ['class' => \yii\grid\CheckboxColumn::className()],
                ['class' => 'yii\grid\SerialColumn'],

                'job_id',
                /*[
                    'label' => '用户名',
                    'value' => 'user.username',
                    'attribute' => 'username',
                    //'filter'=>Html::activeTextInput($searchModel, 'username',['class'=>'form-control'])
                ],*/
                [
                    'label' => '商户名',
                    'value' => 'user.shop_name',
                    'attribute' => 'shop_name',
                ],
                'job_name',
                'num',
                'price_rate',
                //'price_introduction',
                /*[
                    'attribute' => 'price',
                    'value' => function ($model) {
                        return $model->price/100;
                    }
                ],*/

                //'finished',
                [
                    'attribute' => 'expire_time',
                    'format' => ['date','php:Y-m-d H:i:s'],

                ],
                [
                    'attribute' => 'status',
                    'format' =>'raw',
                    'value' => function ($model) {
                        $status = \yii\helpers\ArrayHelper::getValue(Job::statusArray(), $model->status);
                        if ($model->status == Job::STATUS_AWAITING) {
                            return  ColumnDisplay::statusLabel($status, 'label label-info');
                        } elseif ($model->status == Job::STATUS_EXECUTING) {
                            return  ColumnDisplay::statusLabel($status, 'label label-info');
                        } elseif ($model->status == Job::STATUS_NEW) {
                            return  ColumnDisplay::statusLabel($status, 'label label-primary');
                        } elseif ($model->status == Job::STATUS_DRAFT) {
                            return  ColumnDisplay::statusLabel($status, 'label label-default');
                        } elseif ($model->status == Job::STATUS_CANCEL) {
                            return  ColumnDisplay::statusLabel($status, 'label label-warning');
                        } elseif ($model->status == Job::STATUS_COMPLETE) {
                            return  ColumnDisplay::statusLabel($status, 'label label-success');
                        } elseif ($model->status == Job::STATUS_DELETE) {
                            return  ColumnDisplay::statusLabel($status, 'label label-danger');
                        } else {
                            return  ColumnDisplay::statusLabel('error', 'label label-danger');
                        }
                    }
                ],
                /*[
                    'label' => '任务平台',
                    'value' => 'jobDetail.step_symbol',
                    'filter'=>Html::activeTextInput($searchModel, 'step_symbol',['class'=>'form-control'])
                ],*/
                [
                    'attribute' => 'created_time',
                    'format' => ['date','php:Y-m-d H:i:s'],
                ],
                //'job_remark',
                /*[
                    'attribute' => 'ensure_time',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s', $model->ensure_time);
                    }
                ],*/
            ]

            ]); ?>

        </form>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('#job-export').click(function(){
            var filename = prompt('','请输入导出的文件名');
            $('#job-filename').val(filename);
            $('#jobFrom').submit();
        });
    });
    /*function link(url) {
        window.location.href = url;
    }*/

</script>

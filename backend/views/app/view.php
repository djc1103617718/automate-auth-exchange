<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = 'ID:' . $model->app_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'APP列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-view">
    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('更新APP', 'btn btn-warning', 'update')->link(['app/update', 'id' => $model->app_id]);
    $btn->buttonDefault('删除APP', 'btn btn-danger', 'delete')->confirm([
        'title' => '删除APP!',
        'content' => '删除APP,将删除APP应用下的所有动作,这将再也无法创建该APP下的所有任务,你确定删除吗?',
        'data' => ['id' => $model->app_id],
        'url' => ['app/delete', 'id' => $model->app_id],
    ]);
    $btn->end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'app_id',
            'app_name',
            'package_name',
            'search_name',
            [
                'label' => '状态',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    if ($model->status == \backend\models\App::STATUS_NORMAL) {
                        return '正常';
                    } elseif ($model->status == \backend\models\App::STATUS_LOCKING) {
                        return '锁定';
                    } else {
                        return 'error';
                    }
                })
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
    ]) ?>

    <h4 style="color: green;display: inline-block">下一步: &nbsp;</h4><?php $btn = ButtonGroup::begin();$btn->buttonDefault('创建动作', 'btn btn-success', 'add')->link(['app-action/create', 'app_id' => $model->app_id]);ButtonGroup::end(); ?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\AppAction */

$this->title = '动作ID:' . $model->action_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'APP动作'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-action-view">
    <?php
    $button = ButtonGroup::begin();
    $button->buttonDefault('增加自动化', 'btn btn-success', 'add')->link(['app-action-step/create', 'id' => $model->action_id]);
    //$button->button('查看App详情', 'btn btn-primary')->link('app/detail-index');
    $button->buttonDefault('更新动作', 'btn btn-warning', 'update')->link(['app-action/update', 'id' => $model->action_id]);
    $button->buttonDefault('删除动作', 'btn btn-danger', 'delete')->confirm([
        'url' => ['app-action/delete', 'id' => $model->action_id],
        'title' => '删除动作!',
        'content' => '删除该动作将无法恢复,这将无法创建对应的任务,你确定要删除吗?',
        //'data' => ['id' => $model->action_id],
    ]);
    ButtonGroup::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'action_id',
            [
                'label' => 'APP名称',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\App::findOne($model->app_id)->app_name;
                })
            ],
            [
                'attribute' => 'category',
                'value' => \yii\helpers\ArrayHelper::getValue(\backend\models\AppAction::categoryList(), $model->category),
            ],
            'action_name',
            'action_class_name',
            [
                'attribute' => 'status',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\AppAction::getStatusName($model->status);
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

</div>
<h4 style="color: green;display: inline-block">下一步: &nbsp;</h4>
<?php
$btn = ButtonGroup::begin();
$btn->buttonDefault('创建自动化步骤', 'btn btn-success', 'add')->link(['app-action-step/create', 'id' => $model->action_id]);
ButtonGroup::end();
?>


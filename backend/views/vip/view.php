<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\Vip */

$this->title = 'VIP ID:' . $model->vip_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIP列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vip-view">
    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('更新', 'btn btn-warning', 'update')->link(['vip/update', 'id' => $model->vip_id]);
    $btn->buttonDefault('删除', 'btn btn-danger', 'delete')->confirm([
        'url' => ['vip/delete', 'id' => $model->vip_id],
        //'data' => ['id' => $model->vip_id],
        'content' => '一旦删除将无法恢复,你确定要删除吗?'
    ]);
    ButtonGroup::end();
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vip_id',
            'vip_name',
            'description',
            [
                'attribute' => 'status',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    if ($model->status == \backend\models\Vip::STATUS_NORMAL) {
                        return '正常';
                    } elseif ($model->status == \backend\models\Vip::STATUS_DELETE) {
                        return '删除';
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

</div>

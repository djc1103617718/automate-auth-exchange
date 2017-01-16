<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = '用户ID:' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a(Yii::t('app', '更新'), ['更新', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', '删除'), ['删除', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'shop_name',
            'vip_name',
            'email:email',
            [
                'label' => '用户状态',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function($model){
                    if ($model->status == \backend\models\User::STATUS_DELETED) {
                        return '已冻结';
                    } elseif ($model->status == \backend\models\User::STATUS_DEFAULT) {
                        return '未激活';
                    } elseif ($model->status == \backend\models\User::STATUS_ACTIVE) {
                        return '正常';
                    } else {
                        return 'error';
                    }
                })
            ],
            'funds_remaining',
            [
                'label' => '手机号',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return \backend\models\UserDetail::findOne(['user_id' => $model->id])->phone;
                }),
            ],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
$this->title = '账户信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view" style="clear: both; margin-top: 25px">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            [
                'attribute' => 'funds_remaining',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function($model){
                    return $model->funds_remaining/100;
                }),
            ],
            'email:email',
            'shop_name',
            [
                'label' => '手机号',
                'value' =>  \yii\helpers\ArrayHelper::getValue($model,function ($model) {
                    $detail = \frontend\models\UserDetail::findOne(['user_id' => $model->id]);
                    if ($detail) {
                        return $detail->phone;
                    } else {
                        return '';
                    }
                })
            ],
            'vip_name',
            [
                'attribute' =>'status',
                'value' => \yii\helpers\ArrayHelper::getValue(\frontend\models\User::statusList(), $model->status)
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

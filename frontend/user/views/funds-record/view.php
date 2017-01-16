<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\FundsRecord */

$this->title = '记录号:' . $model->funds_record_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '资金记录'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funds-record-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'funds_record_id',
            'record_name',
            [
                'label' => '金额(元)',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function($model, $key){
                    return $model->funds_num/100;
                }),
            ],
            //'user_id',
            [
                'label' => '支出/充值',
                'value' => \yii\helpers\ArrayHelper::getValue($model,function($model, $key) {
                    return \frontend\models\FundsRecord::getTypeName($model->type);
                }),
            ],
            [
                'attribute' => 'current_balance',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function($model) {
                    return $model->current_balance/100;
                })
            ],
            [
                'attribute' => 'created_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>

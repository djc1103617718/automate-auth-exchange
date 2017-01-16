<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vip */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => 'VIP',
]) . $model->vip_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIP列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'VIP:'.$model->vip_id, 'url' => ['view', 'id' => $model->vip_id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="vip-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

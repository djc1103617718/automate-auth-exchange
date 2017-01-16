<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AppAction */

$this->title = Yii::t('app', '更新动作');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'APP动作'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '动作:' . $model->action_id, 'url' => ['view', 'id' => $model->action_id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新动作');
?>
<div class="app-action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

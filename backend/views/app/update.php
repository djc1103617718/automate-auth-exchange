<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = Yii::t('app', '更新APP');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'APP'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'APP:'.$model->app_id, 'url' => ['view', 'id' => $model->app_id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="app-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

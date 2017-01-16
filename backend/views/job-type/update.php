<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\JobType */

$this->title = Yii::t('app', '更新自动化代号');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化代号'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '自动化代号:' . $model->type_id, 'url' => ['view', 'id' => $model->type_id]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新自动化代号');
?>
<div class="job-type-update">

    <?= $this->render('_form', [
        'model' => $model,
        'idToNameArray' => $idToNameArray,
    ]) ?>

</div>

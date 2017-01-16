<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AppAction */
/* @var $form yii\widgets\ActiveForm */

$this->title = '创建APP动作';
?>

<div class="app-action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
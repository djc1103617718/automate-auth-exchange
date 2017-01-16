<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AppAction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-action-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['readonly' => 'readonly']) ?>

    <?= $form->field($model, 'app_name')->textInput(['readonly' => 'readonly'])->label('App名称') ?>

    <?= $form->field($model, 'category')->dropDownList(\backend\models\AppAction::categoryList()) ?>

    <?= $form->field($model, 'action_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action_class_name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

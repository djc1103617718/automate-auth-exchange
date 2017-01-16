<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\JobType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'step_symbol')->textInput() ?>

    <?= $form->field($model, 'job_type_name')->textInput() ?>

    <?= $form->field($model, 'app_id')->dropDownList($idToNameArray)->label('App名称') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\wechatdb\PicRenren;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\PicRenren */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pic-renren-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList(['M' => '男', 'F' => '女']) ?>

    <?= $form->field($model, 'album_mark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pic')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'status')->dropDownList(PicRenren::statusDropDownList()) ?>

    <?= $form->field($model, 'date')->textInput(['readonly' => 'readonly']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

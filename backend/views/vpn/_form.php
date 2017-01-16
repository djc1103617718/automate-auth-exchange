<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\wechatdb\Vpn;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Vpn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vpn-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'vpnname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->dropDownList(\backend\models\wechatdb\City::cityList()) ?>

    <?= $form->field($model, 'vpnip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'used')->dropDownList( Vpn::vpnList(), ['readonly' => 'readonly']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

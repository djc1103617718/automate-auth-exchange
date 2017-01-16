<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Device */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceid')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'vpnid')->dropDownList(\backend\models\wechatdb\Vpn::find()->select('vpnid')->indexBy('vpnid')->asArray()->column()) ?>

    <?= $form->field($model, 'last_connect_time')->textInput(['readonly' => 'readonly']) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->widget(Select2::className(), [
        'data' => \backend\models\wechatdb\City::cityList(),
        //'language' => 'en',
        'options' => ['placeholder' => '请选择城市...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'localip')->textInput() ?>

    <?= $form->field($model, 'last_job_type')->textInput(['readonly' => 'readonly']) ?>

    <?= $form->field($model, 'last_job_param')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'wechat')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

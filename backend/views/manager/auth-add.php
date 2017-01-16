<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '权限添加';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'id' => 'auth-edit-form',
    'options' => ['class' => 'form-horizontal'],
    'action' => '?r=manager/post-auth-add'
]) ?>
<?= $form->field($model, 'name')->textInput(['maxlength'=>64]) ?>
<?= $form->field($model, 'type')->dropDownList( [ '1' => '角色', '2' => '权限' ], [ 'prompt' => '请选择', 'style' => 'width:120px' ] ) ?>
<?= $form->field($model, 'description')->textInput(['maxlength'=>200]) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Edit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

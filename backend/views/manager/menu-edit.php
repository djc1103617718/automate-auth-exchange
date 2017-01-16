<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'menuEdit';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'id' => 'auth-edit-form',
    'options' => ['class' => 'form-horizontal'],
    'action' => '?r=manager/post-menu-edit'
]) ?>
<?= $form->field($model, 'id')->textInput(['readonly'=>true]) ?>
<?= $form->field($model, 'name')->textInput(['maxlength'=>20]) ?>
<?php if( $model->type == 0 ) { ?>
    <?=  $form->field($model, 'type')->dropDownList( [ '0' => '菜单项', '1' => '父菜单栏' ], [ 'prompt' => '请选择', 'style' => 'width:120px' ] ) ?>
<?php } elseif( $model->type == 1 ) { ?>
    <?=  $form->field($model, 'type')->dropDownList( [ '1' => '父菜单栏' ], [ 'readonly'=>true , 'style' => 'width:120px' ] ) ?>
<?php } ?>
<?= $form->field($model, 'parent')->dropDownList( $parent_select, [ 'prompt' => '请选择', 'style' => 'width:120px' ] ) ?>
<?= $form->field($model, 'order')->textInput(['type'=>'number']) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Edit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

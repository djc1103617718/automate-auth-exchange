<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '菜单分配';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'id' => 'menu-edit-form',
    'options' => ['class' => 'form-horizontal'],
    'action' => '?r=manager/post-request-edit'
]) ?>
<?= $form->field($model, 'request')->textInput(['readonly'=>true]) ?>
<?= $form->field($model, 'auth_name')->dropDownList( $auth_range, [ 'prompt' => '请选择', 'style' => 'width:200px' ] ) ?>
<?= $form->field($model, 'menu_id')->dropDownList( $menu_range, [ 'prompt' => '请选择', 'style' => 'width:200px' ] ) ?>
<?= $form->field($model, 'description')->textarea(['maxlength'=>200]) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Edit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

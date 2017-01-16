<style>
    #autheditform-child .checkbox{
        float:left;
        width:250px;
    }
</style>
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'menuEdit';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin( [
    'id'      => 'auth-edit-form',
    'options' => [ 'class' => 'form-horizontal' ],
    'action'  => '?r=manager/post-auth-edit'
] ) ?>
<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
<?= $form->field( $model, 'description' )->textInput( [ 'maxlength' => 200 ] ) ?>

<?php if( $model->type == 1 ) $type = [ '1' => '角色' ];elseif( $model->type == 2 ) $type = [ '2' => '权限' ]; ?>
<?= $form->field( $model, 'type' )->dropDownList( $type, [ 'readonly' => true, 'style' => 'width:120px' ] ) ?>

<?= $form->field( $model, 'child' )->checkboxList( $auth_list ) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton( 'Edit', [ 'class' => 'btn btn-primary' ] ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
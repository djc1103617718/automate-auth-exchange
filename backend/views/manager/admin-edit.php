<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'adminEdit';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin( [
    'id'      => 'admin-edit-form',
    'options' => [ 'class' => 'form-horizontal' ],
    'action'  => '?r=manager/post-admin-edit'
] ) ?>
<?= $form->field( $model, 'id' )->textInput( [ 'readonly' => true ] ) ?>
<?= $form->field( $model, 'username' )->textInput( [ 'disabled' => true ] ) ?>
<?= $form->field( $model, 'email' )->input('email',['maxlength'=>255]) ?>
<?= $form->field( $model, 'auth_role' )->checkboxList($role_list) ?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton( 'Edit', [ 'class' => 'btn btn-primary' ] ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
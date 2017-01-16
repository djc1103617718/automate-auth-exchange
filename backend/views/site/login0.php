<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login" style="position: relative;width:100%; text-align: center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>请登录</p>
    <div class="row" style="position: relative; left:50%;width:100%;">
        <div class="col-lg-5" style="left:-50%;text-align: center;width:100%">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
                'fieldConfig' => [  //统一修改字段的模板
                    'template' => "{label}<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-3\">{error}</div>",

                    'labelOptions' => ['class' => 'col-lg-2 control-label'],  //修改label的样式
                ],
            ]); ?>
            <div class="form-group field-loginform-username required">

                <div> <label for="loginform-username">用户名&nbsp;:&nbsp;</label><input type="text" id="loginform-username"  name="LoginForm[username]" autofocus></div>
                <div style="text-align: center"><p class="help-block help-block-error"></p></div>
            </div>
            <div class="form-group field-loginform-password required">

                <div><label for="loginform-password">密&nbsp; &nbsp;码&nbsp;:&nbsp;</label><input type="password" id="loginform-password" name="LoginForm[password]"></div>
                <div style="text-align: center"><p class="help-block help-block-error"></p></div>
            </div>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                忘记密码了吗?点击 <?= Html::a('重置密码', ['site/request-password-reset']) ?>.
            </div>

            <div class="form-group">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#login-form').yiiActiveForm([{
            "id":"loginform-username",
            "name":"username",
            "container":".field-loginform-username",
            "input":"#loginform-username",
            "error":".help-block.help-block-error",
            "validate":function (attribute, value, messages, deferred, $form) {
                yii.validation.required(value, messages, {
                    "message":"用户名不能为空."});
            }
        },
            {
                "id":"loginform-password",
                "name":"password",
                "container":".field-loginform-password",
                "input":"#loginform-password",
                "error":".help-block.help-block-error",
                "validate":function (attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message":"密码不能为空."});
                }
            },
            {
                "id":"loginform-rememberme",
                "name":"rememberMe",
                "container":".field-loginform-rememberme",
                "input":"#loginform-rememberme",
                "error":".help-block.help-block-error",
                "validate":function (attribute, value, messages, deferred, $form) {
                    yii.validation.boolean(value, messages, {
                        "trueValue":"1",
                        "falseValue":"0",
                        "message":"Remember Me must be either \"1\" or \"0\".","skipOnEmpty":1});
                }
            }], []);
    });
</script>
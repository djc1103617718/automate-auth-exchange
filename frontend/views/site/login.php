<?php \frontend\assets\LoginAndSignUpAsset::register($this);?>
<div class="login-box">
    <div class="login-box-body">
        <p class="login-box-msg">用户登录</p>
        <form id="login-form" action="<?=\yii\helpers\Url::to(['site/login'])?>" method="post">
            <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
            <div class="form-group has-feedback field-loginform-username required">
                <input type="text" id='account' class="form-control form-control_padding" name="LoginForm[account]" placeholder="用户名或邮箱登录">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe607;</i>
                </span>
                <p class="help-block help-block-error" style="color:red;"></p>
            </div>
            <div class="form-group has-feedback field-loginform-password delete_margin_bottom required">
                <input type="password" id='password' class="form-control form-control_padding" name="LoginForm[password]" placeholder="请输入密码">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe608;</i>
                </span>
                <p class="help-block help-block-error" style="color:red;"></p>
            </div>
            <div class="row f14">
                <div class="col-xs-8 delete_padding">
                    <div class="form-group delete_margin_bottom">
                        <div class="checkbox delete_margin_top">
                            <input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked="">
                            <label for="loginform-rememberme">
                                记住密码
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 delete_padding">
                    <div class="form-group delete_margin_bottom">
                        <div class="checkbox delete_margin_top">
                            <!--<label for="loginform-rememberme">-->
                            <p>
                                <a href="<?=\yii\helpers\Url::to(['site/signup']) ?>">注册</a>&nbsp;&nbsp;
                            <a href="<?=\yii\helpers\Url::to(['site/request-password-forget']) ?>">忘记密码?</a>
                            </p><!--</label>-->
                        </div>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="col-xs-6 delete_padding_yzm">
                <div class="form-group">
                    <input id='verificationCode' class="form-control form-control_padding" name="LoginForm[verificationCode]" type="text" placeholder="请输入验证码">
                    <span class="form-control-feedback">
                        <i class="iconfont">&#xe60a;</i>
                    </span>
                    <p class="help-block help-block-error" style="color:red;"></p>
                </div>
            </div>
            <div class="col-xs-6 delete_padding">
                <div class="form-group">
                    <span class="yzm_value btn-block btn-flat" >
                        <?= \yii\captcha\Captcha::widget([
                            'name'=>'captchaimg',
                            'captchaAction'=>'site/captcha',
                            'imageOptions'=>[
                                'id'=>'captchaimg',
                                'title'=>'换一个', 'alt'=>'换一个',
                                'style'=>'cursor:pointer;margin-left:25px;'
                            ],
                            'template'=>'{image}']);
                        ?>
                    </span>
                </div>
                <p class="help-block help-block-error"></p>
            </div>
            <div class="form-group">
                <button id="login-submit" type="button" class="btn btn-primary btn-block btn-flat btn_click" name="login-button">登录</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('#login-submit').click(function() {
            var user = $("#account").val();
            //var username = /^(\w){6,12}/i;
            if (user.length == 0) {
                $("#account").parent('div').find('p').html("账号不可以为空");
                return false;
            } else {
                $("#account").parent('div').find('p').html('');
            }

            var password = $("#password").val();
            var password1 = /^[\w]{6,12}/;
            if (password.length == 0) {
                $("#password").parent('div').find('p').html("密码不可以为空");
                return false;
            } else if (!password1.test(password)) {
                $("#password").parent('div').find('p').html("请输入6-12位的下划线、数字、字母");
                return false;
            } else {
                var url = "<?=\yii\helpers\Url::to(['site/ajax-validate-password'])?>";
                $.ajax({
                    url: url,
                    type:'post',
                    cache: false,
                    dataType: 'json',
                    data: {
                        account: user,
                        password: password
                    },
                    success: function (data) {
                        if (data.code == 0) {
                            $("#password").parent('div').find('p').html('用户名或者密码错误');
                            return false;
                        } else {
                            $("#password").parent('div').find('p').html('');

                            var verificationCode = $("#verificationCode").val();
                            if (verificationCode.length == 0) {
                                $('#verificationCode').parent('div').find('p').html('请输入验证码');
                                return false;
                            } else {
                                var url = "<?= \yii\helpers\Url::to(['site/verification-code-ajax'])?>";
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    cache: false,
                                    dataType: 'json',
                                    data: {
                                        verificationCode: verificationCode
                                    },
                                    success: function (data) {
                                        if (data.code == 0) {
                                            $('#verificationCode').parent('div').find('p').html("验证码错误");
                                            return false;
                                        } else {
                                            $('#login-form').submit();
                                            //$('#verificationCode').parent('div').find('p').html('');
                                        }
                                    },
                                    error: function () {
                                        return false;
                                    }
                                });
                            }
                        }
                    },
                    error: function () {
                        return false;
                    }
                });
            }
        });

    });
</script>
<?php \frontend\assets\LoginAndSignUpAsset::register($this)?>
<div class="login-box register-box">
    <div class="login-box-body">
        <p class="login-box-msg">用户注册</p>
        <form id="signup-form" action="<?=\yii\helpers\Url::to(['site/signup'])?>" method="post">
            <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>" >
            <div class="form-group has-feedback required">
                <input type="text" id="username" class="form-control form-control_padding username" name="SignupForm[username]" placeholder="请输入用户名">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe607;</i>
                </span>
                <p style="color: red" class="help-block help-block-error"></p>
            </div>
            <div class="form-group has-feedback required">
                <input id="password" type="password"class="form-control form-control_padding" name="SignupForm[password]" placeholder="请输入密码">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe608;</i>
                </span>
                <p style="color: red" class="help-block help-block-error"></p>
            </div>
            <div class="form-group has-feedback required">
                <input id="rePassword" type="password"class="form-control form-control_padding" name="SignupForm[rePassword]" placeholder="请重复密码">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe60c;</i>
                </span>
                <p style="color: red" class="help-block help-block-error"></p>
            </div>
            <div class="form-group has-feedback required">
                <input id='shop_name' type="text" class="form-control form-control_padding" name="SignupForm[shop_name]" placeholder="请输入商户名">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe609;</i>
                </span>
                <p style="color: red" class="help-block help-block-error"></p>
            </div>
            <div class="form-group has-feedback field-loginform-password required">
                <input id="email" type="text" class="form-control form-control_padding" name="SignupForm[email]" placeholder="请输入邮箱">
                <span class="form-control-feedback">
                    <i class="iconfont">&#xe60b;</i>
                </span>
                <p style="color: red" class="help-block help-block-error"></p>
            </div>

            <div class="col-xs-6 delete_padding_yzm">
                <div class="form-group">
                    <input id='verificationCode' class="form-control form-control_padding" name="SignupForm[verificationCode]" type="text" placeholder="请输入验证码">
                    <span class="form-control-feedback">
                        <i class="iconfont">&#xe60a;</i>
                    </span>
                    <p style="color: red" class="help-block help-block-error"></p>
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
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat btn_click" name="login-button">注册</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('#username').blur(function() {
            var username = $(this).val();
            var url = "<?=\yii\helpers\Url::to(['site/ajax-validate-username'])?>";
            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                dataType: 'json',
                data: {
                    username: username
                },
                success: function(data){
                    if (data.code==0) {
                        $('#username').parent('div').find('p').html("用户名已经存在");
                        return false;
                    }else{
                        $('#username').parent('div').find('p').html('');
                    }
                },
                error: function () {
                    return false;
                }
            });
        });

        $('#email').blur(function() {
            var email = $(this).val();
            var url = "<?=\yii\helpers\Url::to(['site/ajax-validate-email'])?>";
            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                dataType: 'json',
                data: {
                    email: email
                },
                success: function(data){
                    if (data.code==0) {
                        $('#email').parent('div').find('p').html("邮箱已经存在");
                        return false;
                    }else{
                        $('#email').parent('div').find('p').html('');
                    }
                },
                error: function () {
                    return false;
                }
            });
        });

        $('#verificationCode').blur(function() {
            var verificationCode=$(this).val();
            var url = "<?= \yii\helpers\Url::to(['site/verification-code-ajax'])?>";
            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                dataType: 'json',
                data: {
                    verificationCode: verificationCode
                },
                success: function(data){
                    if (data.code==0) {
                        $('#verificationCode').parent('div').find('p').html("验证码错误");
                        return false;
                    }else{
                        $('#verificationCode').parent('div').find('p').html('');
                    }
                },
                error: function () {
                    return false;
                }
            });
        });

        $("#signup-bottom").submit(function(){

            var user=$("#username").val();
            var username=/^(\w){6,12}/i;
            if(user.length==0){
            $("#username").parent('div').find('p').html("账号不可以为空");
                return false;
            }else if(!username.test(user)){
                $("#username").parent('div').find('p').html("账号只能由6-12的数字、字母或下划线组成");
                return false;
            }else{
                $("#username").parent('div').find('p').html("");
            }

            var password=$("#password").val();
            var password1=/^[\w]{6,12}/;
            if(password.length==0){
                $("#password").parent('div').find('p').html("密码不可以为空");
                return false;
            }else if(!password1.test(password)){
                $("#password").parent('div').find('p').html("请输入6-12位的下划线、数字、字母");
                return false;
            }else{
                $("#password").parent('div').find('p').html("");
            }

            var rePassword=$("#rePassword").val();
            if(rePassword.length==0){
                $("#rePassword").parent('div').find('p').html("密码不可以为空");
                return false;
            }else if(rePassword !== password){
                $("#rePassword").parent('div').find('p').html("两次输入的密码不一致");
                return false;
            }else{
                $("#rePassword").parent('div').find('p').html("");
            }

            var shop_name=$("#shop_name").val();
            if(shop_name.length==0){
                $("#shop_name").parent('div').find('p').html("商户名不可以为空");
                return false;
            }else{
                $("#shop_name").parent('div').find('p').html("");
            }

            var email=$("#email").val();
            var email1=/^[\w]+(\.[\w]+)*@[\w]+(\.[\w]+)+$/;
            if(email.length==0){
                $("#email").parent('div').find('p').html("email不可以为空");
                return false;
            }else if(!email1.test(email)){
                $("#email").parent('div').find('p').html("请输入合法电子邮件");
                return false;
            }else{
                $("#email").parent('div').find('p').html("");
            }

            var verificationCode=$("#verificationCode").val();
            if (verificationCode.length==0) {
                $('#verificationCode').parent('div').find('p').html('请输入验证码');
                return false;
            }else{
                $('#verificationCode').parent('div').find('p').html('');
            }
        });
    })
</script>
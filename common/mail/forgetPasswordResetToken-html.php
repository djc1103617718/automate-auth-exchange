<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/forget-password-reset', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>请点击下面的链接,完成密码重置:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

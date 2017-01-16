<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/forget-password-reset', 'token' => $user->password_reset_token]);
?>
hello <?= $user->username ?>,

请点击下面的链接,完成密码重置:

<?= $resetLink ?>

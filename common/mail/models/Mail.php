<?php

namespace common\mail\models;

use Yii;
use yii\base\Object;

class Mail extends Object
{
    const ADMIN_EMAIL = 'adminEmail';
    public $sendFlag = true;

    /**
     * @param $mailTo
     * @param $url
     * @return bool
     */
    public function signUpMail($mailTo, $url)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($mailTo);
        $mail->setSubject("注册激活");
        $url = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $url;
        $html =  "<th>谢谢你的注册,请点击下面链接完成激活,此链接6小时内有效</th><br/><p><a href='$url' >$url</a></p>" ;
        $mail->setHtmlBody($html);
        if($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }

    /**
     * 激活注册发生错误时发送给管理员的邮件
     * @return bool
     */
    public function signUpErrorMailToAdmin()
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo(Yii::$app->params[self::ADMIN_EMAIL]);
        $mail->setSubject("注册激活邮件失败");
        $mail->setTextBody('注册发送激活邮件发生错误');
        if($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }

    /**
     * 定时执行脚本执行失败时触发邮件
     * @return bool
     */
    public function accountJobLogError()
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo(Yii::$app->params[self::ADMIN_EMAIL]);
        $mail->setSubject("定时执行脚本执行失败");
        $mail->setTextBody('执行时间:' . date('Y-m-d H:i:s', time()));
        if($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }

    /**
     * 重置密码
     * @param $user
     * @return bool
     */
    public function resetPasswordRequest($user)
    {
        $mail = Yii::$app->mailer->compose(
            ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
            ['user' => $user]
        );
        $mail->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot']);
        $mail->setTo($user->email);
        $mail->setSubject(Yii::$app->name . '密码重置');
        if ($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }

    public function resetEmail($mailTo)
    {
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($mailTo);
        $mail->setSubject("重置邮箱");
        $mail->setTextBody('恭喜您,重置邮箱成功');
        if($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }

    public function forgetPasswordRequest($user)
    {
        $mail = Yii::$app->mailer->compose(
            ['html' => 'forgetPasswordResetToken-html', 'text' => 'forgetPasswordResetToken-text'],
            ['user' => $user]
        );
        $mail->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot']);
        $mail->setTo($user->email);
        $mail->setSubject(Yii::$app->name . '密码重置');
        if ($mail->send()) {
            return true;
        } else {
            $this->sendFlag = false;
            return false;
        }
    }
}
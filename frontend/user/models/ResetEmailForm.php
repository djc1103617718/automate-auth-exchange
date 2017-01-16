<?php

namespace frontend\user\models;

use common\mail\models\Mail;
use yii\base\Model;
use frontend\models\User;
use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;

class ResetEmailForm extends Model
{
    public $email;
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '这个邮箱地址已经被占用'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('app', '邮箱'),
        ];
    }

    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function resetEmail()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$this->_user) {
            throw new ForbiddenHttpException();
        }
        $this->_user->email = $this->email;
        $this->_user->removePasswordResetToken();
        $mail = new Mail();
        if ($mail->resetEmail($this->email)) {
            if ($this->_user->update()) {
                return true;
            }
        }
        return false;
    }
}

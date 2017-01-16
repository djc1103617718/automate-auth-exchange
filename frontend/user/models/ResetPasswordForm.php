<?php
namespace frontend\user\models;

use yii\base\Model;
use frontend\models\User;
use yii\base\InvalidParamException;
use yii\web\ForbiddenHttpException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $rePassword;
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
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['rePassword', 'required'],
            ['rePassword', 'string', 'min' => 6],
            ['rePassword', 'compare', 'compareAttribute' => 'password', 'operator' => '===', 'message' => '两次输入的密码必须一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('app', '密码'),
            'rePassword' => \Yii::t('app', '重复密码'),
        ];
    }

    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function resetPassword()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$this->_user) {
            throw new ForbiddenHttpException();
        }
        $this->_user->password_hash = User::setUserPassword($this->password);
        $this->_user->removePasswordResetToken();
        if ($this->_user->update()) {
           return true;
        }
        return false;
    }
}

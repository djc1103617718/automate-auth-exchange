<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    //email or username
    public $account;
    public $password;
    public $rememberMe = true;

    private $_user;
    public $verificationCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['account', 'required', 'message' => '账号不能为空'],
            [['password'], 'required', 'message'=>'密码不能为空'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verificationCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', '用户名'),
            'password' => Yii::t('app', '密码'),
            'rememberMe' => Yii::t('app', '记住我'),
            'verificationCode' => Yii::t('app', '验证码'),

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或者密码错误!');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[account]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->account);
            if ($this->_user === null) {
                $this->_user = User::findByEmail($this->account);
            }
        }

        return $this->_user;
    }
}

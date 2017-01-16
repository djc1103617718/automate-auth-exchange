<?php
namespace frontend\user\models;

use Yii;
use yii\base\Model;
use frontend\models\User;
use yii\web\ForbiddenHttpException;

/**
 * Password reset request form
 */
class EmailResetRequestForm extends Model
{
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'passwordValidate'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', '密码'),
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function passwordValidate($attribute, $params)
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user) {
            throw new ForbiddenHttpException();
        }
        if ($user->validatePassword($this->password)){
            return true;
        }
        $this->addError($attribute, '密码不正确');
        return false;
    }

    /**
     * @return bool
     */
    public function setEmailResetToken()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => User::findOne(Yii::$app->user->id),
        ]);
        if (!$user) {
            return false;
        }
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        if (!$user->save()) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     */
    public static function getEmailRestToken()
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => Yii::$app->user->id,
        ]);
        if ($user->password_reset_token) {
            return $user->password_reset_token;
        }

        throw new ForbiddenHttpException();
    }
}

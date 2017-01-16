<?php

namespace frontend\user\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use frontend\models\User;
use frontend\models\UserDetail;

class UserUpdateForm extends Model
{
    public $id;
    public $phone;
    //public $email;
    public $username;
    public $shop_name;

    public function rules()
    {
        return [
            [['shop_name', 'id'], 'required'],
            [['id'], 'integer'],
            ['phone', 'trim'],
            ['phone', 'string', 'length' => 11],
            ['phone', 'phoneRule'],
            [['username'], 'string', 'max' => 255],
            [['shop_name'], 'string', 'max' => 46],
            [['username'], 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            //[['email'], 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            //'email' => Yii::t('app', '邮箱'),
            'username' => Yii::t('app', '用户名'),
            'shop_name' => Yii::t('app', '商户名'),
            'phone' => Yii::t('app', '手机号'),
        ];
    }

    public function phoneRule()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function update()
    {
        if (!$this->validators) {
            //var_dump($this->errors);die;
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = User::findOne($this->id);
            if (!$user) {
                throw new Exception('Not Found Page');
            }
            $user->setAttributes($this->toArray());
            if ($user->update() === false) {
                throw new Exception('user 更新失败');
            }
            $userDetail = UserDetail::findOne(['user_id' => $this->id]);
            if (!$userDetail) {
                throw new Exception('Not Found Page');
            }
            $userDetail->setAttributes($this->toArray());
            if ($userDetail->update() === false) {
                throw new Exception('user detail 更新失败');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
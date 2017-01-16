<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/12 15:18
 *
 */

namespace backend\models\post;

use app\models\AuthAssignment;
use backend\models\User;
use yii\base\Exception;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;


class AdminEditForm extends \yii\base\Model {
    public $id;
    public $username;
    public $email;
    public $auth_role;

    public function rules() {
        return [
            //必须填写
            [ [ 'id' ], 'required' ],
            //parent可为空，为空时转null
            [['email'], 'default','value'=>''],
            //email格式
            ['email', 'email'],
        ];
    }

    public function save(){
        $user = User::find()->where( [ 'id' => $this->id ] )->one();
        $auth_manager = \Yii::$app->authManager;
        $all_roles = $auth_manager->getRoles();
        if( $user ) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $user->email = $this->email;
                $user->save();
                $have_list = $auth_manager->getRolesByUser( $this->id );
                $have_list = array_keys($have_list);
                $remove_list = array_diff( $have_list, $this->auth_role );
                $add_list = array_diff( $this->auth_role,$have_list );
                foreach( $remove_list as $key => $val ) {
                    $item = AuthAssignment::find()->where( ['user_id'=>$this->id,'item_name'=>$val] )->one();
                    if( $item ) $item->delete();
                }
                foreach( $add_list as $key => $val ) {
                    if( !empty($all_roles[ $val ]) ) {
                        $auth_manager->assign( $all_roles[ $val ], $this->id );
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        }
        else return false;

    }

}
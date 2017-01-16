<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/12 15:18
 *
 */

namespace backend\models\post;

use app\models\AuthItem;
use app\models\AuthItemChild;
use yii\base\Exception;
use yii\base\Model;

class AuthEditForm extends \yii\base\Model {
    public $name;
    public $type;
    public $description;
    public $rule_name;
    public $child;

    public function rules() {
        return [
            //必须填写
            [ [ 'name', 'description', 'type' ], 'required' ],
            //类型
            ['type', 'in', 'range' => [1, 2]]
        ];
    }

    public function save() {
        $auth_manager = \Yii::$app->authManager;
        if( $this->type == 1 ) {
            $auth = $auth_manager->getRole( $this->name );
        }
        elseif( $this->type == 2 ) {
            $auth = $auth_manager->getPermission( $this->name );
        }

        if( !empty( $auth ) ) {
            $transaction  = \Yii::$app->db->beginTransaction();
            try {
                $auth->description = $this->description;
                $child_list = AuthItemChild::find()->where( [ 'parent' => $this->name ] )->indexBy( 'child' )->asArray()->all();
                $child_list = array_keys( $child_list );
                $model_child_list = $this->child;
                $remove_list = array_diff( $child_list, $model_child_list );
                $add_list = array_diff( $model_child_list, $child_list );
                foreach ( $remove_list as $key => $val ) {
                    $child = $auth_manager->getPermission( $val );
                    if( empty( $child ) ) $child = $auth_manager->getRole( $val );
                    if( empty( $child ) ) continue;
                    if( $auth_manager->hasChild( $auth, $child ) ) {
                        $auth_manager->removeChild( $auth, $child );
                    }
                }
                foreach ( $add_list as $key => $val ) {
                    if( empty( $child_list[ $val ] ) ) {
                        $child = $auth_manager->getPermission( $val );
                        if( empty( $child ) ) $child = $auth_manager->getRole( $val );
                        if( empty( $child ) ) continue;
                        if( !$auth_manager->hasChild( $auth, $child ) ) {
                            $auth_manager->addChild( $auth, $child );
                        }
                    }
                }
                $auth_manager->update( $this->name, $auth );
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        else return false;
    }

    public function add(){
        $auth_manager = \Yii::$app->authManager;
        if( $this->type == 2 ) {
            $auth = $auth_manager->createPermission( $this->name );
            $auth->description = $this->description;
            $auth_manager->add( $auth );
        }
        elseif( $this->type == 1 ) {
            $auth = $auth_manager->createRole( $this->name );
            $auth->description = $this->description;
            $auth_manager->add( $auth );
        }
    }

}
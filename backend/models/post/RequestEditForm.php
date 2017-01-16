<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/12 15:18
 *
 */

namespace backend\models\post;

use app\models\AuthItem;
use app\models\AuthItemMenu;
use app\models\Menu;
use backend\models\validator\TestValidator;
use yii\base\Model;

class RequestEditForm extends \yii\base\Model {
    public $request;
    public $description;
    public $auth_name;
    public $menu_id;

    public function rules() {
        //权限码范围
        $auth_range = AuthItem::find()->select('name')->where( ['type'=>2] )->asArray()->all();
        $auth_used_range = AuthItemMenu::find()->select( ['auth_name'] )->where( ['and',['!=','request',$this->request],['is not','auth_name',null]] )->asArray()->all();
        foreach( $auth_range as $key => $val ) {
            $auth_range[$key] = $val['name'];
        }
        foreach( $auth_used_range as $key => $val ) {
            $auth_used_range[$key] = $val['auth_name'];
        }
        $auth_range = array_diff($auth_range,$auth_used_range);

        //菜单范围
        $temp_menu_range = Menu::find()->select( 'id,name' )->where( ['type'=>0] )->asArray()->all();
        $temp_menu_used_range = AuthItemMenu::find()->select( [ 'menu_id' ] )->where( ['and',['!=','request',$this->request],['is not','menu_id',null]] )->asArray()->all();
        $menu_range = [];
        $menu_used_range = [];
        foreach( $temp_menu_range as $key => $val ) {
            $menu_range[ $val['id'] ] = $val['id'];
        }
        foreach( $temp_menu_used_range as $key => $val ) {
            $menu_used_range[ $val['menu_id'] ] = $val['menu_id'];
        }
        $menu_range = array_diff_key($menu_range,$menu_used_range);

        return [
            //必须填写
            [ [ 'request', 'description' ], 'required' ],
            //范围控制
            ['auth_name', 'in', 'range' => $auth_range ],
            ['menu_id', 'in', 'range' => $menu_range ],
            //去除空格
            [['description'], 'trim'],
            [['auth_name', 'menu_id'], 'default'],

        ];
    }

    public function save() {
        $auth = AuthItemMenu::find()->where( [ 'request' => $this->request ] )->one();
        if( $auth ) {
            $auth->description = $this->description;
            $auth->auth_name = $this->auth_name;
            $auth->menu_id = $this->menu_id;
            $auth->save();
        }
        else {
            $auth = new AuthItemMenu();
            $auth->request = $this->request;
            $auth->description = $this->description;
            $auth->auth_name = $this->auth_name;
            $auth->menu_id = $this->menu_id;
            $auth->insert();
        }
    }

}
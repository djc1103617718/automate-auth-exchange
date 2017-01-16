<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/12 15:18
 *
 */

namespace backend\models\post;

use app\models\Menu;
use yii\base\Model;
use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;


class MenuEditForm extends \yii\base\Model {
    public $id;
    public $type;
    public $parent;
    public $name;
    public $order;

    public function rules() {
        return [
            //必须填写
            [ [ 'name' ], 'required' ],
            //parent可为空，为空时转null
            [['parent'], 'default','value'=>null],
            [['order'], 'default','value'=>0],
            //数字验证
            [ [ 'id', 'type', 'parent', 'order' ], NumberValidator::className(), 'min'=>0, 'integerOnly'=>true],
        ];
    }

    public function save() {
        $menu = Menu::find()->where( [ 'id' => $this->id ] )->one();
        if( $menu ) {
            $menu->type = $this->type;
            $menu->name = $this->name;
            $menu->parent = $this->parent;
            $menu->order = $this->order;
            $res = $menu->save();
            if( $res ) return true;
            else return false;
        }
        else return false;
    }

    public function add() {
        $menu = new Menu();
        $menu->type = $this->type;
        $menu->name = $this->name;
        $menu->parent = $this->parent;
        $menu->order = $this->order;
        $menu->insert();
    }
}
<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/11 16:19
 *
 */
namespace backend\widgets;

use app\models\Menu;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class NavMenuWidget extends Widget {
    public $menus;
    public $listView = '/widgets/_nav-menu-list';
    public $itemView = '/widgets/_nav-menu-item';

    public function init() {
        parent::init();
    }

    public function run() {
        //获取所有菜单，并以父元素id作为键名
        $menus = Menu::find()->select('menu.*,aim.request')->leftJoin('auth_item_menu aim','aim.menu_id = menu.id')->orderBy( 'order desc,type desc' )->asArray()->all();
        $this->menus = [];
        foreach( $menus as $key => $val ) {
            if( empty($val['parent']) ) $val['parent'] = 0;
            $this->menus[ $val['parent'] ][] = $val;
        }

        $res = $this->renderAll();
        return $res;
    }

    public function renderAll() {
        $rows = [];
        foreach( $this->menus[0] as $key => $val ) {
            $rows[] = $this->renderOne( $val );
        }
        $content = implode( "\n", $rows );

        return $this->getView()->render( $this->listView, [ 'content'  => $content ] );
    }

    public function renderOne( $item ) {
        $content = $this->getView()->render( $this->itemView, [ 'model'   => $item ,'menus' => $this->menus] );
        return $content;
    }
}
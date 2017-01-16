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

class SideMenuWidget extends Widget {
    public $current_root_menu;
    public $menus;
    public $listView = '/widgets/_side-menu-list';
    public $itemView = '/widgets/_side-menu-item';

    public function init() {
        parent::init();
    }

    public function run() {
        if( !empty($_COOKIE['current_menu_id']) ) $this->current_root_menu = $_COOKIE['current_menu_id'];
        if( empty( $this->current_root_menu ) ) $this->current_root_menu = 1;

        //获取所有菜单，并以父元素id作为键名
        $menus = Menu::find()->select('menu.*,aim.request')->leftJoin('auth_item_menu aim','aim.menu_id = menu.id')->orderBy( 'order desc,type desc' )->asArray()->all();
        $this->menus = [];
        foreach( $menus as $key => $val ) {
            if( empty($val['parent']) ) $val['parent'] = 0;
            $this->menus[ $val['parent'] ][] = $val;
        }

        $rows = [];
        foreach( $this->menus[ 0 ] as $key => $val ) {
            if( empty( $this->menus[ $val['id'] ] ) ) continue;
            $rows[] = '<ul id="demo-list" class="demo-list side-menu-'.$val['id'].'" style="display: none">'.$this->renderAll( $this->menus[ $val['id'] ] ).'</ul>';
        }
        $content = implode( "\n", $rows );
        return $this->getView()->render( $this->listView, [ 'content'  => $content ] );
    }

    public function renderAll( $list ) {
        $rows = [];
        foreach( $list as $key => $val ) {
            $rows[] = $this->renderOne( $val );
        }
        $content = implode( "\n", $rows );

        return $content;
    }

    public function renderOne( $item ) {
        $rows = [];
        if( $item['type'] == 1 ) {
            if( !empty( $this->menus[ $item['id'] ] ) ) {
                $rows[] = $this->renderAll( $this->menus[ $item['id'] ] );
            }
        }
        $item_content = implode( "\n", $rows );
        $content = $this->getView()->render( $this->itemView, [ 'model' => $item,'menus' => $this->menus, 'content'=> $item_content ] );
        return $content;
    }
}
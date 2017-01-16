<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/11 16:19
 *
 */
namespace backend\widgets;

use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class ArrayListWidget extends Widget {
    public $array;
    public $itemView;
    public $listView;
    public $page;
    public $pageSize;
    public $pageInfo;
    public $width;
    public $actions;

    public function init() {
        parent::init();
    }

    public function run() {
        $this->page = \Yii::$app->request->get( 'page', 1 );
        $res = $this->renderAll();
        return $res;
    }

    public function renderAll() {
        $rows = [ ];

        $this->pageInfo = new Pagination( [ 'totalCount' => count( $this->array ), 'pageSize' => $this->pageSize ] );

        $temp_arr = array_slice( $this->array, ( $this->page - 1 ) * $this->pageSize, $this->pageSize );

        foreach ( $temp_arr as $item ) {
            $rows[] = $this->renderOne( $item );
        }
        $content = implode( "\n", $rows );

        $keys = [ ];
        foreach( $temp_arr as $arr ) {
            $keys = array_keys( $arr );
            break;
        }

        return $this->getView()->render( $this->listView, [
            'content'  => $content,
            'pageInfo' => $this->pageInfo,
            'keys'     => $keys,
            'width'    => $this->width,
            'actions'  => $this->actions
        ] );
    }

    public function renderOne( $item ) {
        $content = $this->getView()->render( $this->itemView, [
            'model'   => $item,
            'width'   => $this->width,
            'actions' => $this->actions
        ] );
        return $content;
    }
}
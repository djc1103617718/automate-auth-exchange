<?php
namespace common\components\grid;

use common\helper\views\ColumnDisplay;
use yii\base\Exception;
use yii\helpers\Html;
use Yii;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $header = '操作';
    public $headerOptions = ['class' => 'actions'];
    public $contentOptions = ['class' => 'actions'];
    public $showLabel = true;

    /**
     * @param $actionName
     * @return string
     * @throws Exception
     */
    protected function getDefaultLabel($actionName)
    {
        if ($this->showLabel === false) {
            return '';
        }
        if ($actionName === 'view') {
            $label = '查看';
        } elseif ($actionName === 'update') {
            $label = '更新';
        } elseif ($actionName === 'delete') {
            $label = '删除';
        } else {
            throw new Exception('没有找到对应的默认actionName');
        }
        return $label;
    }
    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a("<i class='fa fa-eye'>{$this->getDefaultLabel('view')}</i>", $url, [
                    'title' => Yii::t('yii', 'View'),
                ]);
            };
        }
        if (!isset($this->buttons['view.modal'])) {
            $this->buttons['view.modal'] = function ($url, $model) {
                return Html::a("<i class='fa fa-eye'>{$this->getDefaultLabel('view')}</i>", $url, [
                    'title' => Yii::t('yii', 'View'),
                    'data-action' => 'get.modal',
                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a("<i class='fa fa-pencil-square'>{$this->getDefaultLabel('update')}</i>", $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-action' => 'get.modal',
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return ColumnDisplay::operatingDelete(['url' => $url]);
            };
        }
        /*if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return Html::a("<i class='fa fa-trash'>{$this->getDefaultLabel('delete')}</i>", $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-action' => 'delete.grid-view',
                ]);
            };
        }*/
        /*if (!isset($this->buttons['password'])) {
            $this->buttons['password'] = function($url, $model) {
                return Html::a("<i class='fa fa-lock'></i>", $url, [
                    'title' => 'Password',
                    'data-action' => 'get.modal'
                ]);
            };
        }*/
    }
}
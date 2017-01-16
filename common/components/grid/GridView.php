<?php
namespace common\components\grid;

use Closure;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

class GridView extends \yii\grid\GridView 
{
    public $layout = <<<EOT
<div class="grid-heading"><div class="grid-summary">{summary}</div>{toolbar}</div>{items}{pager}
EOT;
    // public $tableOptions = ['class' => 'table table-striped table-bordered table-hover'];
    // public $headerRowOptions = ['class' => 'heading'];
    public $options = ['class' => 'grid-view', 'data-init' => 'init.grid-view'];
    public $actions = [];
    public $moreActions = [];
    public $moreActionsTemplate = '';
    public $actionsTemplate = '';//{create} {multi-delete}
    public $title;
    public $sectionLoad;

    /**
     * @var callable a callback that creates a button URL using the specified model information.
     * The signature of the callback should be the same as that of [[createUrl()]].
     * If this property is not set, button URLs will be created using [[createUrl()]].
     */
    public $urlCreator;

    public $controller;
    
    /**
     * Runs the widget.
     */
    public function run()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $view = $this->getView();
        $view->registerJs("jQuery('#$id').yiiGridView($options).trigger('init');");
        
        if ($this->sectionLoad) {
            $view->registerJs("jQuery('#$id').on('click', '.pagination a, .sort a', function(event) {jQuery('{$this->sectionLoad}').load(this.href);event.stopImmediatePropagation();return false;});");
        }
        
        \yii\widgets\BaseListView::run();
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case "{toolbar}":
                return $this->renderToolbar();
            case "{title}":
                return $this->title;
            default:
                return parent::renderSection($name);
        }
    }

    public function renderToolbar()
    {
        $this->initDefaultActions();
        $tpl = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function($matches) {
            $name = $matches[1];
            if (isset($this->actions[$name])) {
                $url = $this->createUrl($name);

                return call_user_func($this->actions[$name], $url);
            } else {
                return '';
            }
        }, $this->actionsTemplate);
        return Html::tag('div', $tpl . $this->renderMore(), ['class' => 'grid-toolbar btn-group']);
    }

    public function renderMore()
    {
        if ($this->moreActionsTemplate) {
            $tpl = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function($matches) {
                $name = $matches[1];
                if (isset($this->moreActions[$name])) {
                    $url = $this->createUrl($name);

                    return '<li>' . call_user_func($this->moreActions[$name], $url) . '</li>';
                } else {
                    return '<li>' . $matches[0] . '</li>';
                }
            }, $this->moreActionsTemplate);

            $btn = '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></button>';
            $menu = Html::tag('ul', $tpl, ['class' => 'dropdown-menu dropdown-menu-right', 'role' => 'menu']);
            return Html::tag('div', $btn . $menu, ['class' => 'btn-group']);
        }

        return '';
    }

    protected function initDefaultActions()
    {
        if (!isset($this->actions['create'])) {
            $this->actions['create'] = function($url) {
                return Html::tag('a', '<i class="fa fa-plus fa-lg"></i> Create', [
                    'class' => 'btn btn-success',
                    'href' => $url,
                    'data-action' => 'get.modal',
                ]);
            };
        }
        if (!isset($this->actions['multi-delete'])) {
            $this->actions['multi-delete'] = function($url) {
                return Html::tag('a', '<i class="fa fa-trash fa-lg"></i> ' . Yii::t('yii', 'Delete'), [
                    'class' => 'btn btn-danger',
                    'href' => $url,
                    'data-action' => 'multi-delete.grid-view',
                ]);
            };
        }
        if (!isset($this->moreActions['export'])) {
            $this->moreActions['export'] = function ($url) {
                $get = Yii::$app->request->get();
                $amp = strpos($url, '?') === false ? '?' : '&';
                unset($get['r'], $get['_pjax']);
                $url = $url . $amp . http_build_query($get);
                return Html::tag('a', '<i class="fa fa-share fa-lg"></i> ' . Yii::t('yii', 'Export'), [
                    'href' => $url,
                    'data-pjax' => 0
                ]);
            };
        }
        if (!isset($this->moreActions['refresh'])) {
            $this->moreActions['refresh'] = function ($url) {
                return Html::tag('a', '<i class="fa fa-refresh fa-lg"></i> ' . Yii::t('yii', 'Refresh'), [
                    'href' => $url,
                    'data-action' => 'refresh.page',
                ]);
            };
        }
    }

    public function createUrl($action)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action);
        } else {
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }
}
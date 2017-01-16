<?php

namespace common\helper\views;

use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Url;

/**
 * 此插件依赖 ui-dialog.css,dialog-plus.js,jquery*.js
 * 此插件用于生成按钮组,也可用于生成单个按钮
 *
 * Class ButtonGroup
 * @package common\helper\views
 */
class ButtonGroup extends Model
{
    public static $buttonString = '';
    const DEFAULT_BUTTON_TYPE_ADD = 'add';
    const DEFAULT_BUTTON_TYPE_ADD_MORE = 'add2';
    const DEFAULT_BUTTON_TYPE_UPDATE = 'update';
    const DEFAULT_BUTTON_TYPE_VIEW = 'view';
    const DEFAULT_BUTTON_TYPE_DELETE = 'delete';
    const DEFAULT_BUTTON_TYPE_LIST = 'list';

    public static function defaultButtonList()
    {
        return [
            'fa fa-eye' => self::DEFAULT_BUTTON_TYPE_VIEW,
            'fa fa-pencil-square-o' => self::DEFAULT_BUTTON_TYPE_UPDATE,
            'fa fa-trash' => self::DEFAULT_BUTTON_TYPE_DELETE,
            'fa fa-plus-square' => self::DEFAULT_BUTTON_TYPE_ADD,
            'fa fa-plus-circle' => self::DEFAULT_BUTTON_TYPE_ADD_MORE,
            'fa fa-list-alt' => self::DEFAULT_BUTTON_TYPE_LIST,
        ];
    }

    public static function begin()
    {
        $buttonModel = new self();
        static::$buttonString = '<div style="clear: both;margin:10px 0px;"><div class="btn-group">';
        return $buttonModel;
    }

    public static function end()
    {
        echo static::$buttonString .= '</div></div>';
    }

    /*public static function button($label, $class='btn btn-default', $confirmOptions=null)
    {
        if ($confirmOptions) {
            $confirmDelete = new ConfirmDelete();
            $confirmDelete->title = isset($confirmOptions['title'])? $confirmOptions['title']:$confirmDelete->title;
            $confirmDelete->content = isset($confirmOptions['content'])? $confirmOptions['content']:$confirmDelete->content;
            $confirmDelete->url = isset($confirmOptions['url'])? $confirmOptions['url']:$confirmDelete->url;
            $confirmDelete->data = isset($confirmOptions['data'])? json_encode($confirmOptions['data']):null;
        }
        static::$buttonString .= "<button type='button' class='{$class}' onclick="."confirmDelete('{$confirmDelete->url}','{$confirmDelete->data}','{$confirmDelete->title}','{$confirmDelete->content}')".">$label</button>";
    }*/

    /**
     * @param string $label
     * @param string $class
     * @param null|array $options
     * @param null|string $icon
     * @return object $this
     */
    public function button($label, $class, $options=null, $icon=null)
    {
        if ($icon) {
            $str = "<button type='button' class='%s' %s onclick={function}><span class='$icon'></span> %s</button>";
            static::$buttonString .= sprintf($str, $class, static::getOptionString($options), $label);
            return $this;
        } else {
            $str = "<button type='button' class='%s' %s onclick={function}>%s</button>";
            static::$buttonString .= sprintf($str, $class, static::getOptionString($options), $label);
            return $this;
        }
    }

    /**
     * @param string $label
     * @param string $class 按钮类名
     * @param string $type 图片类名
     * @param null $options css
     * @return $this
     */
    public function buttonDefault($label, $class, $type, $options=null)
    {
        if (in_array($type, static::defaultButtonList())) {
            $defaultClass = static::getDefaultStyle($type);
            $str = "<button type='button' class='%s' %s onclick={function}><span class='$defaultClass'></span> %s</button>";
        }  else {
            $str = "<button type='button' class='%s' %s onclick={function}>%s</button>";
        }
        static::$buttonString .= sprintf($str, $class, static::getOptionString($options), $label);

        return $this;
    }

    public function confirm($config)
    {
        $confirmModel = new Confirm($config);
        static::$buttonString = str_replace('{function}', $confirmModel->functionString, static::$buttonString);
    }

    /**
     * @param $config
     */
    public function link($config)
    {
        $linkModel = new Link($config);
        static::$buttonString = str_replace('{function}', $linkModel->functionString, static::$buttonString);
    }

    private static function getOptionString($options)
    {
        $str = '';
        if (!$options) {
            return $str;
        }
        foreach ($options as $key => $value) {
            $str .= "$key='{$value}' ";
        }
        return $str;
    }

    /**
     * @param $defaultButtonType
     * @return mixed
     */
    private static function getDefaultStyle($defaultButtonType)
    {
        return array_search($defaultButtonType, static::defaultButtonList());
    }

    /**
     * @param $url
     * @return string
     * @throws Exception
     */
    public static function getUrl($url)
    {
        if (!$url) {
            throw new Exception('没有url参数');
        }
        if (is_string($url)) {
            return Url::to([$url]);
        }
        if (is_array($url)) {
            return Url::to($url);
        }
    }

}

/**
 * 可以使按钮带模态框效果,并可以请求ajax
 *
 * Class Confirm
 * @package common\helper\views
 */
class Confirm
{
    const FUNCTION_NAME = 'confirmDelete(%s)';
    public $title = '删除提示!';
    public $content = '删除以后将不能恢复,确定要删除吗?';
    public $url = '#';
    public $data = null;
    public $functionString;
    public $method = 'post';

    public function __construct($config)
    {
        $this->title = isset($config['title'])? $config['title']:$this->title;
        $this->content = isset($config['content'])? $config['content']:$this->content;
        $this->url = isset($config['url'])? ButtonGroup::getUrl($config['url']):$this->url;
        $this->data = isset($config['data'])? json_encode($config['data']):null;
        $this->method = isset($config['method'])? $config['method']:$this->method;

        $this->functionString = sprintf(self::FUNCTION_NAME, "'$this->url','$this->data','$this->title','$this->content','$this->method'");
    }
}

/**
 * 可以使按钮带超链接式的效果
 *
 * Class Link
 * @package common\helper\views
 */
class Link
{
    const FUNCTION_NAME = 'link(%s)';
    public $url = '#';
    public $functionString;

    public function __construct($url)
    {
        $this->url = isset($url)? ButtonGroup::getUrl($url):$this->url;
        $this->functionString = sprintf(self::FUNCTION_NAME, "'$this->url'");
    }
}
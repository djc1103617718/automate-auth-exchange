<?php

namespace common\helper\views;

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * 该类实现了一些便利的方法,来为视图中的gridView或detailView插件的列(属性)的显示服务
 *
 * Class ColumnDisplay
 * @package common\helper
 */
class ColumnDisplay
{
    /**
     * 默认的几种status Label的样式
     */
    const LABEL_STYLE_DEFAULT = 'default';
    const LABEL_STYLE_SUCCESS = 'success';
    const LABEL_STYLE_DANGER = 'danger';
    const LABEL_STYLE_WARNING = 'warning';
    const LABEL_STYLE_INFO = 'info';
    const LABEL_STYLE_PRIMARY = 'primary';

    /**
     * 默认的几种status Label的样式数组
     */
    public static $labelStyle = [
        self::LABEL_STYLE_DEFAULT,
        self::LABEL_STYLE_DANGER,
        self::LABEL_STYLE_INFO,
        self::LABEL_STYLE_PRIMARY,
        self::LABEL_STYLE_SUCCESS,
        self::LABEL_STYLE_WARNING,
    ];

    /**
     * 从默认的样式中获取样式
     *
     * @param $name @默认的样式名
     * @return string
     */
    public static function getStatusLabelStyle($name)
    {
        return in_array($name, static::$labelStyle) ? 'label label-' . $name : '';
    }

    /**
     * 生成状态属性的标签
     * @param string $content
     * @param array|string $style @['fa', 'fa-success'] 或者 'fa fa-success'
     * @param string $tag
     * @return string
     */
    public static function statusLabel($content, $style, $tag='span')
    {
        return Html::tag($tag, $content, ['class' => $style]);
    }

    /**
     * $array 形如:
     * [
     *     1 => ['label' => '正常', 'style' => 'success'],
     *     3 => ['label' => '删除', 'style' => 'default'],
     *     ......
     * ];
     * 或者
     * [
     *     1 => ['正常', 'success'],
     *     3 => ['删除', 'default'],
     *     ......
     * ]
     * 或者 当参数$type != null 时,可以如下:
     * [
     *     1 => ['正常', 'label label-success'],
     *     3 => ['删除', 'label label-default'],
     *     ......
     * ]
     *
     * $array的键是status的实际的值;返回的是 Html::tag();
     *
     * @param $value
     * @param $array
     * @param int $type 默认指定$style是从 static::$labelStyle里取值,如果指定$type则$style为原生的class
     * @return string Html::tag();
     */
    public static function displayStatus($value, $array, $type = null)
    {
        $keys = array_keys($array);
        $index = array_search($value, $keys);
        if ($index !== false) {
            $status = $array[$keys[$index]];
            if (isset($status['label']) && isset($status['style'])) {
                if ($type === null) {
                    $style = static::getStatusLabelStyle($status['style']);
                } else {
                    $style = $status['style'];
                }
                return static::statusLabel($status['label'], $style);
            } else {
                if ($type === null) {
                    $style = static::getStatusLabelStyle($status[1]);
                } else {
                    $style = $status[1];
                }

                return static::statusLabel($status[0], $style);
            }
        } else {
            return static::statusLabel('error', static::getStatusLabelStyle(static::LABEL_STYLE_DANGER));
        }
    }

    /**
     * 生成格式化的时间用于gridView和detailView等小部件中
     *
     * @param $attribute
     * @return array
     */
    public Static function dateValue($attribute)
    {
        return [
            'attribute' => $attribute,
            'format' => ['date', 'php:Y-m-d H:i:s'],
        ];
    }

    /**
     * 生成gridView的操作按钮
     * @param $label
     * @param $style
     * @param $url
     * @param $options
     * @return string
     */
    public Static function operating($label, $style, $url, $options = [])
    {
        return Html::a("<span class='{$style}'>{$label}&nbsp;</span>", $url, $options) ;
    }

    /**
     * 生成删除图标按钮,点击删除时,删除前会有弹出框效果
     *
     * @param array $confirmDelete 弹出框配置数组
     *  [
     *      'url'=> '选择弹出框的`是`后,会请求的url',
     *      'data' => [url请求传递的数据,是键值对数组],
     *      'method' => 'post|get',
     *      'title' => '弹框的标题',
     *      'content' => '弹框的内容',
     *  ]
     * @param string $label 按钮名
     * @param string $style 按钮样式
     * @param array $options 按钮标签的属性配置数组
     * @return string
     */
    public Static function operatingDelete($confirmDelete, $label = '删除', $style = 'fa fa-trash', $options = [])
    {
        $url = Url::to($confirmDelete['url']);
        $data = isset($confirmDelete['data'])? json_encode($confirmDelete['data']): null;
        $title = isset($confirmDelete['title'])? $confirmDelete['title']: '删除警告!';
        $content = isset($confirmDelete['content'])? $confirmDelete['content']: '删除以后将不能恢复,你确定要删除吗?';
        $method = isset($confirmDelete['method'])? $confirmDelete['method']: 'post';
        $confirmDelete = "confirmDelete('$url','$data','$title','$content','$method')";
        $options = array_merge($options, ['onclick'=>"$confirmDelete"]);
        return static::operating($label, $style, '#', $options);
    }

}
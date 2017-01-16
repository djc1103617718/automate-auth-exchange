<?php

namespace common\components\price;

use yii\base\Exception;

/**
 * 价格类的抽象规范:
 * 注意这里的两个关联:
 * 1、建议在类里定义价格字段名常量,该字段要么是job中的字段,要么是app_action_step中的job_param里的name,
 * 因此字段名与类中定义该字段的名字必须对应上,同时如果是job_param中的字段,不仅子类中对该字段的定义必须对应,
 * 还有其它设置也必须一致,比如取值。
 * 类通过他们来实现对价格因素的字段的价格管理和设置。
 * 2、价格类名,即子类的类名与app_action中的action_class_name一致
 * Class ActionPriceAbstract
 * @package common\components\price
 */
abstract class ActionPriceAbstract
{
    //保存该对象
    protected $objTaskTemplateForm;

    public $error_msg = [];

    /**
     * ActionPriceAbstract constructor.
     * @param $taskTemplateForm
     * @throws Exception
     */
    public function __construct($taskTemplateForm = null)
    {
        if ($taskTemplateForm != null) {
            if (!isset($taskTemplateForm->job_params)) {
                throw new Exception('该对象缺少job_params属性');
            }
            if (!$taskTemplateForm->action_id) {
                throw new Exception('该对象缺少action_id值');
            }
            $this->objTaskTemplateForm = $taskTemplateForm;
        }
    }

    /**
     * 如果没有错误必须返回整数,否则返回布尔值false
     * @return int|false
     */
    abstract public function getPrice();

    /**
     * 获得价格简介的字符串
     * @return string
     */
    abstract public function getPricesIntroduction();

    /**
     * 获得与job_param无关的价格因素数组用于前台模版视图
     * 形如:['job里的字段名' => ['label' => 'xxx', 'values' => ['xxx', 'xxx', ...]], ....]
     * @return array
     */
    abstract  public function getPublicPriceList();

    /**
     * 对价格因素进行验证
     * @return bool
     */
    abstract protected function validatePrice();

    /**
     * @return array
     */
    abstract public function getErrors();

}

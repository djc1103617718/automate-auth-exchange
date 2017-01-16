<?php

namespace common\components\price;

use Yii;
use frontend\user\models\TaskTemplateForm;
use yii\base\Exception;

class Price
{
    const SUBCLASS_ROUTE = '\common\components\price\subclass\\';
    /**
     * @var
     */
    private $_priceObject;

    /**
     * 获得单价
     * @return int|bool
     */
    /*public function getPrice()
    {
        return $this->_priceObject->getPrice();
    }*/

    /**
     * Price constructor.
     * @param object $taskTemplateForm
     * @param $priceClassName
     */
    public function __construct($priceClassName, $taskTemplateForm = null)
    {
        $className = self::SUBCLASS_ROUTE . $priceClassName;
        $this->_priceObject = new $className($taskTemplateForm);
    }

    /**
     * 获得价格因素的简介
     * @return string
     */
    /*public function getPricesIntroduction()
    {
        return $this->_priceObject->getPricesIntroduction();
    }*/

    /**
     * 获得与job_param无关的价格因素数组
     * 形如:['job里的字段名' => ['label' => 'xxx', 'values' => ['xxx', 'xxx', ...]], ....]
     * @param $priceClassName
     * @return array
     */
    public static function getPublicPriceList($priceClassName)
    {
        $className = self::SUBCLASS_ROUTE . $priceClassName;
        $class = new $className();
        return $class->getPublicPriceList();
    }

    /**
     * @return string|[]
     */
    public function getErrors()
    {
        if (!$this->_priceObject->getErrors()) {
            return null;
        }
        return implode(',', $this->_priceObject->getErrors());
    }

    public function __call($name, $arguments)
    {
        try {
            return $this->_priceObject->$name($arguments);
        } catch (Exception $e) {
            throw new Exception('未定义的方法');
        }
    }
}
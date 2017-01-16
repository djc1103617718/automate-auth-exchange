<?php

namespace common\components\price\subclass;

use yii\base\Exception;
use common\components\price\ActionPriceAbstract;

class Test1 extends ActionPriceAbstract
{
    /**
     * 价格因素的job里字段名,与job_params无关加前缀PUBLIC定义;与job_params有关加PRITE前缀;
     */
    const PUBLIC_PRICE_RATE = 'price_rate';
    // price_rate的值选项
    const RATE_HIGH = '快速(单价￥0.06)';
    const RATE_LOW = '低速(单价￥0.04)';
    const RATE_SPEED = '中速(单价￥0.05)';

    /**
     * 与job_params无关的价格因素数组
     * @var array
     */
    protected $publicPriceFactory = [
        self::PUBLIC_PRICE_RATE,
    ];

    /**
     * @return int|bool

     */
    public function getPrice()
    {
        if (!$this->validatePrice()){
            $this->error_msg[] = '价格验证失败!';
            return false;
        }
        $priceRate = self::PUBLIC_PRICE_RATE;
        if (!$this->objTaskTemplateForm->$priceRate) {
            $this->error_msg[] = '没有找到对应的价格因素,可能是价格因素名称不一致或其它app设置错误!';
            return false;
        }
        switch ($this->objTaskTemplateForm->$priceRate)
        {
            case self::RATE_HIGH :
                return 6;
            case self::RATE_SPEED :
                return 5;
            case self::RATE_LOW :
                return 4;
            default : $this->error_msg[] = '没有匹配到对应的价格'; return false;
        }
    }

    public function getPricesIntroduction()
    {
        $price_rate = self::PUBLIC_PRICE_RATE;
        return $this->objTaskTemplateForm->$price_rate;
    }

    /**
     * 获取与job_params无关的价格因素的数组用于前端模版
     * @return array
     */
    public function getPublicPriceList()
    {
        return [
            self::PUBLIC_PRICE_RATE => [
                'label' => '任务速度',
                'values' => [
                    self::RATE_HIGH,
                    self::RATE_SPEED,
                    self::RATE_LOW,
                ],
            ],
        ];
    }

    /**
     * @return bool
     */
    protected function validatePrice()
    {
        $priceRate = self::PUBLIC_PRICE_RATE;
        if (empty($this->objTaskTemplateForm->$priceRate)) {
            $this->error_msg[] = '任务速度不能为空';
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->error_msg;
    }
}
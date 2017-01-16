<?php

namespace common\components\price\subclass;

use common\models\AppActionStep;
use yii\base\Exception;
use common\components\price\ActionPriceAbstract;

class TestPrice extends ActionPriceAbstract
{
    /**
     * 价格因素的job里字段名,与job_params无关加前缀PUBLIC;与job_params有关加PRIVATE前缀;
     */
    const PUBLIC_PRICE_RATE = 'price_rate';
    // price_rate的值选项
    const RATE_HIGH = '快速(单价￥0.06)';
    const RATE_LOW = '低速(单价￥0.04)';
    const RATE_SPEED = '中速(单价￥0.05)';

    // job_params里的与价格相关的字段testJobParams
    const PRIVATE_TEST_JOB_PARAM = 'testJobParams';
    //字段testJobParams的值选项
    const TEST_JOB_PARAM_TEST1 = 'test1(￥1.0)';
    const TEST_JOB_PARAM_TEST2 = 'test2(￥1.5)';
    const TEST_JOB_PARAM_TEST3 = 'test3(￥2.0)';

    private $_jobParamValueList = [];
    private $_priceIntroduction;
    private $_price;


    /**
     * @var array
     */
    protected static $jobParamNameList = [
        self::PRIVATE_TEST_JOB_PARAM,
    ];

    /**
     * @return int|bool
     * @throws Exception
     */
    public function getPrice()
    {
        if (!$this->_price) {
            $needle = $this->generateNeedle();
            $this->setPriceAndIntroduction($needle);
        }
        if ($this->validatePrice()) {
            return $this->_price;
        }
        $this->error_msg[] = '获取价格失败!';
        return false;
    }

    /**
     * 用于前端
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
     * @return mixed
     */
    public function getPricesIntroduction()
    {
        if ($this->_priceIntroduction) {
            return $this->_priceIntroduction;
        }
        $this->setPriceAndIntroduction($this->generateNeedle());
        return $this->_priceIntroduction;
    }

    /**
     * @return bool
     */
    protected function validatePrice()
    {
        if (!$this->_price) {
            $needle = $this->generateNeedle();
            $this->setPriceAndIntroduction($needle);
        }
        if ($this->_price == 'error'){
            $this->error_msg[] = '缺少价格因素';
            return false;
        }
        return true;
    }

    /**
     * 定义价格所有可能的模式
     * @return array
     */
    private function priceRules()
    {
        return [
            ['pattern' => '', 'value' => 'error'],
            ['pattern' => self::TEST_JOB_PARAM_TEST1, 'value' => 100],
            ['pattern' => self::TEST_JOB_PARAM_TEST2, 'value' => 150],
            ['pattern' => self::TEST_JOB_PARAM_TEST3, 'value' => 200],
            ['pattern' => self::RATE_LOW, 'value' => 4],
            ['pattern' => self::RATE_LOW . ',' . self::TEST_JOB_PARAM_TEST1, 'value' => 210],
            ['pattern' => self::RATE_LOW . ',' . self::TEST_JOB_PARAM_TEST2, 'value' => 250],
            ['pattern' => self::RATE_LOW . ',' . self::TEST_JOB_PARAM_TEST3, 'value' => 300],
            ['pattern' => self::RATE_SPEED, 'value' => 5],
            ['pattern' => self::RATE_SPEED . ',' . self::TEST_JOB_PARAM_TEST1, 'value' => 250],
            ['pattern' => self::RATE_SPEED . ',' . self::TEST_JOB_PARAM_TEST2, 'value' => 300],
            ['pattern' => self::RATE_SPEED . ',' . self::TEST_JOB_PARAM_TEST3, 'value' => 350],
            ['pattern' => self::RATE_HIGH, 'value' => 6],
            ['pattern' => self::RATE_HIGH . ',' . self::TEST_JOB_PARAM_TEST1, 'value' => 400],
            ['pattern' => self::RATE_HIGH . ',' . self::TEST_JOB_PARAM_TEST2, 'value' => 450],
            ['pattern' => self::RATE_HIGH . ',' . self::TEST_JOB_PARAM_TEST3, 'value' => 500],
        ];
    }

    /**
     * @param $action_id
     * @return array|\yii\db\ActiveRecord[]
     */
    private function getSteps($action_id)
    {
        $stepModels = AppActionStep::find()
            ->select(['step_symbol', 'job_param'])
            ->where(['status' => AppActionStep::STATUS_NORMAL, 'action_id' => $action_id])
            ->asArray()
            ->all();
        return $stepModels;
    }

    /**
     * 获得job_param的字段名和实际的对应表单值对应
     * @param $jobParams
     * 形如 ['arg1_11' => '快速(1.0元)', 'arg2_11' => 'xxx']
     * @param $action_id
     * @return array
     * 形如 [字段名 => value,...]
     * @throws Exception
     */
    protected function jobParamNameToValues($jobParams, $action_id)
    {
        if ($this->_jobParamValueList) {
            return $this->_jobParamValueList;
        }
        $arr = [];
        $jobParamsPriceNames = $this->getJobParamPriceName($action_id);
        foreach ($jobParams as $key => $jobParam) {
            foreach ($jobParamsPriceNames as $k => $jobParamsPriceName) {
                if ($key == $k) {
                    $arr[$jobParamsPriceName] = $jobParam;
                }
            }
        }
        if (!$arr) {
            throw new Exception('找不到价格字段名和值的对应');
        }
        $this->_jobParamValueList = $arr;
        return $this->_jobParamValueList;
    }

    /**
     * 从app_action_step的job_param里获取与价格相关的字段
     * @param $action_id
     * @return array
     * 返回数据形如[arg1_11 => '字段名', arg2_11 => '', arg1_14 => '']
     * @throws Exception
     */
    protected function getJobParamPriceName($action_id)
    {
        if (!$stepModels = $this->getSteps($action_id)) {
            throw new Exception('找不到对应的自动化价格参数');
        }

        $jobParamsPriceName = [];
        foreach ($stepModels as $stepModel) {
            $jobParams = json_decode($stepModel['job_param']);
            if (empty($jobParams)) {
                throw new Exception('action缺少job_param参数');
            }
            foreach ($jobParams as $key => $value) {//key =>arg; value => [data_type => xxx,data_list => xxx]
                if (in_array($value->input_box_name, self::$jobParamNameList)) {
                    $jobParamsPriceName[$key . '_' . $stepModel['step_symbol']] = $value->input_box_name;
                }
            }
        }
        return $jobParamsPriceName;
    }

    /**
     * 生成匹配元素
     * @return string
     */
    protected function generateNeedle()
    {
        $property = self::PUBLIC_PRICE_RATE;
        $needle = $this->objTaskTemplateForm->$property;
        if (!isset($this->_jobParamValueList[self::PRIVATE_TEST_JOB_PARAM])) {
            $this->_jobParamValueList = $this->jobParamNameToValues($this->objTaskTemplateForm->job_params, $this->objTaskTemplateForm->action_id);
        }
        if ($this->_jobParamValueList[self::PRIVATE_TEST_JOB_PARAM] && $needle) {
            $needle .= ',';
        }
        $needle .= $this->_jobParamValueList[self::PRIVATE_TEST_JOB_PARAM];

        return $needle;
    }

    /**
     * @param $needle
     * @return string
     * @throws Exception
     */
    protected function matchPriceRules($needle)
    {
        foreach ($this->priceRules() as $value) {
            if ($value['pattern'] == $needle) {
                $this->_price = $value['value'];
                $this->_priceIntroduction = $value['pattern'];
                break;
            }
        }

        if (!$this->_price) {
            throw new Exception('找不到对应的匹配');
        }
        return $this->_price;
    }

    /**
     * @param $needle
     * @throws Exception
     */
    protected function setPriceAndIntroduction($needle)
    {
        foreach ($this->priceRules() as $value) {
            if ($value['pattern'] == $needle) {
                $this->_price = $value['value'];
                $this->_priceIntroduction = $value['pattern'];
                break;
            }
        }
        if (!$this->_price || !$this->_priceIntroduction) {
            throw new Exception('获取不到价格');
        }

    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->error_msg;
    }
}
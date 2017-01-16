<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\base\Exception;
use common\helper\ArrayAide;
use common\helper\StringAide;
use common\helper\JobParam;
use yii\helpers\ArrayHelper;

error_reporting( E_ALL&~E_NOTICE );
class TaskTemplateForm extends Model
{
    public $num;
    public $job_remark;
    public $expire_time;
    public $action_id;
    public $job_params;
    public $price;
    public $price_rate;
    public $job_id;
    public $price_introduction;
    const FAILURE_CREATE_JOB_MSG = '任务创建失败!';
    const PRICE_FACTORY = 'price';

public function rules()
    {
        return [
            [['num', 'action_id', 'job_params',], 'required'],
            [['job_remark','expire_time', 'job_id', 'price_rate', 'price_introduction'], 'safe'],
            [['num'], 'integer'],
            ['expire_time', 'isExpired'],
            ['expire_time', 'string'],
            [['num', 'price'], 'validateFundsRemaining', 'on' => 'job_create'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),
            [
                'draft-update' => [
                    'num',
                    'job_params',
                    'price',
                    'job_remark',
                    'expire_time',
                    'job_id',
                    'price_rate',
                    'price_introduction'
                ]
            ]
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $parentLabels =  parent::attributeLabels();
        $parentLabels['num'] = Yii::t('app', '任务量');
        $parentLabels['job_remark'] = Yii::t('app', '任务备注');
        $parentLabels['expire_time'] = Yii::t('app', '过期时间');
        return $parentLabels;
    }

    /**
     * 账户余额验证
     * @param $attribute
     * @param $param
     */
    public function validateFundsRemaining($attribute,$param)
    {
        if (!$this->hasErrors()) {
            $user_id = Yii::$app->user->getId();
            $totalPayment = $this->price * $this->num;
            $funds_remaining = User::findOne($user_id)->funds_remaining;
            if ($totalPayment > $funds_remaining) {
                $this->addError($attribute, '账户余额不足');
            }
        }
    }

    /**
     * @param $attribute
     * @param $param
     */
    public function isExpired($attribute, $param)
    {
        if (!$this->hasErrors()) {
            if ($this->expire_time) {
                if (strtotime($this->expire_time) < time()) {
                    $this->addError($attribute, '过期时间无效');
                }
            }
        }
    }

    /**
     *  验证用户输入的 job_params参数
     * @return bool
     */
    public function validateJobParams()
    {
        $stepMolds = $this->getStepMolds();
        if (!$stepMolds) {
            $this->addError('action_id', '没有找到对应的应用!');
        }

        foreach ($stepMolds as $step_id => $argList) {
            foreach ($argList as $arg => $argMold) {
                //对jobParam进行一些初始化操作
                $this->jobParamInit($step_id, $arg, $argMold);
                //验证开始
                if (!$this->argRequired($step_id, $arg, $argMold)) {
                    return false;
                }
                if (!($this->argInteger($step_id, $arg, $argMold))) {
                    return false;
                }
                if (!($this->argFloat($step_id, $arg, $argMold))) {
                    return false;
                }
                if (!$this->argInDataList($step_id, $arg, $argMold)){
                    return false;
                }
                if (!$this->argLength($step_id, $arg, $argMold)) {
                    return false;
                }
                if (!$this->argSize($step_id, $arg, $argMold)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     */
    private function jobParamInit($step_id, $arg, $argMold)
    {
        $this->setArgDefaultValue($step_id, $arg, $argMold);
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     */
    private function setArgDefaultValue($step_id, $arg, $argMold)
    {
        if (($this->job_params[$step_id][$arg] === '') && ($argMold[JobParam::VALUE_DEFAULT] !== '')) {
            $this->job_params[$step_id][$arg] = $argMold[JobParam::VALUE_DEFAULT];
        }
    }

    /**
     * 验证单个输入框的值是否为空
     *
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     */
    private function argRequired($step_id, $arg, $argMold)
    {
        if ($this->job_params[$step_id][$arg] === '') {
            if (($argMold[JobParam::ALLOWED_NULL] == JobParam::ALLOWED_NULL_FALSE) && ($argMold[JobParam::VALUE_DEFAULT] === '')) {
                $inputName = $argMold[JobParam::INPUT_BOX_NAME];
                $this->addError($inputName, $inputName . '不能为空');
                return false;
            }
        }
        return true;
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     */
    private function argInteger($step_id, $arg, $argMold)
    {
        if ($argMold[JobParam::DATA_TYPE] != JobParam::DATA_TYPE_INT || $this->job_params[$step_id][$arg] === '') {
            return true;
        }
        $str = $this->job_params[$step_id][$arg];

        if (StringAide::isInteger($str)) {
            return true;
        }
        $inputName = $argMold[JobParam::INPUT_BOX_NAME];
        $this->addError($inputName, $inputName . '必须是整数');
        return false;
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     */
    private function argFloat($step_id, $arg, $argMold)
    {
        if ($argMold[JobParam::DATA_TYPE] != JobParam::DATA_TYPE_FLOAT || $this->job_params[$step_id][$arg] === '') {
            return true;
        }
        if (!is_numeric($this->job_params[$step_id][$arg])) {
            $inputName = $argMold[JobParam::INPUT_BOX_NAME];
            $this->addError($inputName, $inputName . '必须是float数据或int数据类型');
            return false;
        }
        return true;
    }
    
    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     */
    private function argLength($step_id, $arg, $argMold)
    {
        if ($this->job_params[$step_id][$arg] === '' || $argMold[JobParam::DATA_LENGTH] === '') {
            return true;
        }
        $constraintString = $argMold[JobParam::DATA_LENGTH];
        $res = JobParam::constraintCalculate(StringAide::strLength($this->job_params[$step_id][$arg]), $constraintString);
        if ($res['flag'] === false) {
            $consArr = explode(',', $constraintString);
            $leftString = $consArr[0];
            $rightString = $consArr[1];
            $sign = JobParam::getSign($leftString, $rightString);
            $msg = JobParam::getConstraintMsg($argMold[JobParam::INPUT_BOX_NAME], $sign, $res, 2);
            $this->addError($argMold[JobParam::INPUT_BOX_NAME], $msg);
            return false;
        }

        return true;
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     */
    private function argSize($step_id, $arg, $argMold)
    {
        if ($this->job_params[$step_id][$arg] === '' || $argMold[JobParam::DATA_SIZE] === '') {
            return true;
        }
        $constraintString = $argMold[JobParam::DATA_SIZE];
        $res = JobParam::constraintCalculate($this->job_params[$step_id][$arg], $constraintString);
        if ($res['flag'] === false) {
            $consArr = explode(',', $constraintString);
            $leftString = $consArr[0];
            $rightString = $consArr[1];
            $sign = JobParam::getSign($leftString, $rightString);
            $msg = JobParam::getConstraintMsg($argMold[JobParam::INPUT_BOX_NAME], $sign, $res);
            $this->addError($argMold[JobParam::INPUT_BOX_NAME], $msg);
            return false;
        }

        return true;
    }

    /**
     * @param $step_id
     * @param $arg
     * @param $argMold
     * @return bool
     * @throws Exception
     */
    private function argInDataList($step_id, $arg, $argMold)
    {
        if (in_array($argMold[JobParam::INPUT_BOX_TYPE], [JobParam::INPUT_BOX_TYPE_RADIO,JobParam::INPUT_BOX_TYPE_SELECT])) {
            if ($argMold[JobParam::DATA_LIST] === '') {
                throw new Exception('没有枚举值');
            }
            if (!in_array($this->job_params[$step_id][$arg], $argMold[JobParam::DATA_LIST])) {
                $inputName = $argMold[JobParam::INPUT_BOX_NAME];
                $this->addError($inputName, $inputName . '的值不在可选值里');
                return false;
            }
        }
        return true;
    }

    /**
     * @return array|[]
     */
    private function getStepMolds()
    {
        $stepMolds = AppActionStep::find()
            ->select('job_param')
            ->where(['action_id' => $this->action_id, 'status' => AppActionStep::STATUS_NORMAL])
            ->indexBy('step_id')
            ->asArray()
            ->column();
        if (empty($stepMolds)) {
            return [];
        }
        $arrMolds = [];
        foreach ($stepMolds as $step_id => $step) {
            $step = ArrayHelper::toArray(json_decode($step));
            $arrMolds[$step_id] = $step;
        }
        return $arrMolds;
    }

    /**
     * 格式化job_param数据并对step排序用于前端模版视图
     * $step_id[$arg] => ['input_box_name'=> 'xxx', 'input_box_type' => 'ppp', .....]
     * 形如:[[11]['arg1']=>['input_box_name' => 'xxx', ....], [12]['arg1'] => [...]]
     * @param $action_id
     * @return array|null
     */
    public static function formatJobParamForView($action_id)
    {
        $actionSteps = static::getActionStep($action_id);
        if (!$actionSteps) {
            return null;
        }
        $stepArray = [];
        foreach ($actionSteps as $stepId => $jobParam) {
            $jobParam = ArrayHelper::toArray(json_decode($jobParam));
            $jobParam = static::jobParamSort($jobParam);
            foreach ($jobParam as $arg => $item) {
                //对数据格式化为固定形式的数组保存
                $stepArray[$stepId][$arg] = $item;
            }
        }
        return $stepArray;
    }

    /**
     * @param $action_id
     * @param $job_id
     * @return array|null
     */
    public static function formatJobParamForUpdate($action_id, $job_id)
    {
        $actionSteps = static::getActionStep($action_id);
        if (!$actionSteps) {
            return null;
        }

        $jobDetails = static::getJobDetailList($job_id, $action_id);

        $stepArray = [];
        foreach ($actionSteps as $stepId => $jobParam) {
            $jobParam = ArrayHelper::toArray(json_decode($jobParam));
            $jobParam = static::jobParamSort($jobParam);
            foreach ($jobParam as $arg => $item) {
                if (isset($jobDetails[$stepId][$arg])) {
                    $item['value'] = $jobDetails[$stepId][$arg];
                }
                //对数据格式化为固定形式的数组保存
                $stepArray[$stepId][$arg] = $item;
            }
        }
        return $stepArray;
    }

    /**
     * @param $job_id
     * @param $action_id
     * @return array
     */
    private static function getJobDetailList($job_id, $action_id)
    {
        $jobDetails = JobDetail::find()->where(['job_id' => $job_id])->asArray()->all();
        $list = [];
        foreach ($jobDetails as $jobDetail) {
            $step = AppActionStep::findOne(['status' => AppActionStep::STATUS_NORMAL, 'action_id' => $action_id, 'step_symbol' => $jobDetail['step_symbol']]);
            $list[$step->step_id] = $jobDetail;
        }
        return $list;
    }

    /**
     * @param $action_id
     * @return array
     */
    private static function getActionStep($action_id)
    {
        $actionSteps = AppActionStep::find()
            ->select(['job_param'])
            ->where(['action_id' => $action_id, 'status' => AppActionStep::STATUS_NORMAL])
            ->orderBy(['sort' => SORT_DESC])
            ->indexBy('step_id')
            ->asArray()
            ->column();
        return $actionSteps;
    }

    /**
     * 排序
     *
     * @param $job_param
     * @return array|bool
     */
    private static function jobParamSort($job_param)
    {
        return ArrayAide::multiArraySort($job_param, JobParam::INPUT_BOX_SORT);
    }

    /**
     * array [$app_id => $app_name]
     * @return array|null
     */
    public static function appIdToName()
    {
        $app = App::find()
            ->select(['app_id', 'app_name'])
            ->where(['status' => 1])
            ->asArray()
            ->all();
        if (!$app) {
            return null;
        }
        $idToNameArray = [];
        foreach ($app as $v) {
            $idToNameArray[$v['app_id']] = $v['app_name'];
        }
        return $idToNameArray;
    }
}
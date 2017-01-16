<?php

namespace backend\models;

use common\helper\ArrayAide;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\helper\JobParam;
use yii\helpers\ArrayHelper;

/**
 * Class ActionStepForm
 * @package backend\models
 */
class ActionStepForm extends Model
{
    public $action_name;
    public $step_id;
    public $action_id;
    public $job_param;
    public $step_symbol;
    public $params;
    public $status;
    public $sort;
    public $isNewRecord = 1;
    public $errorMsg;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['step_symbol', 'action_id'], 'required'],
            [['step_symbol', 'action_id'], 'integer'],
            [['job_param'], 'string', 'max' => 625],
            [['sort'], 'integer', 'max' => 255, 'message' => '排序值不能大于255'],
            [['action_name', 'params', 'status', 'step_id'], 'safe'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => AppAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'step_id' => Yii::t('app', '自动化步骤ID'),
            'action_id' => Yii::t('app', '动作ID'),
            'step_symbol' => Yii::t('app', '自动化步骤代号'),
            'job_param' => Yii::t('app', '自动化步骤参数'),
            'action_name' => Yii::t('app', '动作名'),
            'params' => Yii::t('app', '参数'),
            'status' => Yii::t('app', '状态'),
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    /**
     * create or update app_action_step
     * @return bool
     */
    public function save()
    {
        $actionStepModel = $this->findOneActionStepById($this->step_id);

        if (!$actionStepModel) {
            $actionStepModel = new AppActionStep();
        }
        $actionStepModel->setAttributes(ArrayHelper::toArray($this));

        if (!$actionStepModel->step_id) {
            if ($actionStepModel->save()){
                $this->isNewRecord = 0;
                return true;
            }
        } else {
            $this->isNewRecord = 0;
            if ($actionStepModel->update()) {
                return true;
            }
        }

        $error = array_values($actionStepModel->errors);
        if (!empty($error) && isset($error[0][0])) {
            $this->addError('table', $error[0][0]);
        }

        return false;
    }

    /**
     * 对jobParam数据进行初始化的处理
     *
     * @return bool
     */
    public function jobParamInit()
    {
        if ($this->formatDataList()) {
            return true;
        }
        return false;
    }

    /**
     * 对job_param参数的枚举值中进行格式化为关联数组的形式
     *
     * @return bool
     */
    private function formatDataList()
    {
        if (empty($this->job_param)) {
            $this->errorMsg[] = ['job_param', '缺少job_param参数'];
            return false;
        }
        foreach ($this->job_param as $arg=>$item) {
            if (empty($item[JobParam::DATA_LIST])) {
                continue;
            }
            $dataList = explode(',', $item[JobParam::DATA_LIST]);
            $dataListArray = [];
            foreach ($dataList as $string) {
                $arr = explode('=>', $string);
                $len = count($arr);
                if ($len === 1) {
                    $dataListArray[$arr[0]] = $arr[0];
                } elseif ($len === 2) {
                    $dataListArray[$arr[0]] = $arr[1];
                } else {
                    $this->errorMsg[] = [JobParam::DATA_LIST, '数据枚举格式错误'];
                    return false;
                }
            }
            $this->job_param[$arg][JobParam::DATA_LIST] = $dataListArray;
        }

        return true;
    }

    /**
     * @param $jobParam
     * @return array
     */
    public static function formatJobParamForUpdate($jobParam)
    {
        $jobParam = ArrayHelper::toArray(json_decode($jobParam));
        $jobParam = static::dataListChangeString($jobParam);
        return $jobParam;
    }

    /**
     * @param $jobParam
     * @return mixed
     */
    /*private static function changeNULL($jobParam)
    {
        foreach ($jobParam as $arg => $item) {
            foreach ($item as $key => $value) {
                if ($value === NULL) {
                    $jobParam[$arg][$key] = '';
                }
            }
        }
        return $jobParam;
    }*/

    /**
     * @param array $jobParam
     * @return mixed
     */
    private static function dataListChangeString($jobParam)
    {
        foreach ($jobParam as $arg => $item) {
            $dataString = '';
            if ($list = $item[JobParam::DATA_LIST]) {
                $i = 1;
                foreach ($list as $key => $val) {
                    if ($i === 1) {
                        $dataString .= $key . '=>' . $val;
                    } else {
                        $dataString .= ',' . $key . '=>' . $val;
                    }
                    $i++;
                }
            }
            $jobParam[$arg][JobParam::DATA_LIST] = $dataString;
        }
        return $jobParam;
    }

    /**
     * @param $action_id
     * @param $step_symbol
     * @return AppActionStep
     */
    public function findOneActionStep($action_id, $step_symbol){
        return AppActionStep::findOne(['step_symbol' => $step_symbol, 'action_id' => $action_id]);
    }

    /**
     * @param $step_id
     * @return AppActionStep
     */
    public function findOneActionStepById($step_id){
        return AppActionStep::findOne($step_id);
    }

}
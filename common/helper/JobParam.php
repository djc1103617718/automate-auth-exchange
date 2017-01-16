<?php

namespace common\helper;

use yii\base\Exception;
use common\helper\StringAide;

/**
 * 对JobParam进行验证
 * Class JobParam
 * @package common\helper
 */
error_reporting( E_ALL&~E_NOTICE );
class JobParam
{
    // 以下为jobParam里json数据对应的字段名
    const INPUT_BOX_NAME = 'input_box_name';
    //表单类型
    const INPUT_BOX_TYPE = 'input_box_type';
    const INPUT_BOX_TYPE_INPUT = 'input';
    const INPUT_BOX_TYPE_RADIO = 'radio';
    const INPUT_BOX_TYPE_SELECT = 'select';
    const INPUT_BOX_TYPE_TEXTAREA = 'textarea';
    //数据类型
    const DATA_TYPE = 'data_type';
    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_INT = 'integer';
    const DATA_TYPE_FLOAT = 'float';

    const DATA_LIST = 'data_list';
    const DATA_LENGTH = 'data_length';
    const DATA_SIZE = 'data_size';
    const INPUT_BOX_SORT = 'sort';
    const VALUE_DEFAULT = 'default';
    //为空否
    const ALLOWED_NULL = 'allowed_null';
    const ALLOWED_NULL_TRUE = 1;
    const ALLOWED_NULL_FALSE = 2;


    /**
     * @return array
     */
    public static function dataTypeList()
    {
        return [
            self::DATA_TYPE_STRING,
            self::DATA_TYPE_INT,
            self::DATA_TYPE_FLOAT,
        ];
    }

    /**
     * return array
     */
    public static function inputBoxTypeList()
    {
        return [
            self::INPUT_BOX_TYPE_INPUT,
            self::INPUT_BOX_TYPE_SELECT,
            self::INPUT_BOX_TYPE_RADIO,
            self::INPUT_BOX_TYPE_TEXTAREA,
        ];
    }

    public $error_msg = [];

    /**
     * @param array $jobParams
     * 形如['arg1'=>['input_box_type' => 'input', 'data_type' => 'string', ..], 'arg2' => []]
     * @return bool
     */
    public function validateJobParam($jobParams)
    {
        foreach ($jobParams as $jobParam) {
            if (!$this->inputBoxNameRequired($jobParam)) {
                return false;
            }
            if (!$this->dataLengthSpecification($jobParam)){
                return false;
            }
            if (!$this->dataSizeSpecification($jobParam)){
                return false;
            }
            if (!$this->dataType($jobParam)) {
                return false;
            }
            if (!$this->inputBoxNameSort($jobParam)) {
                return false;
            }
            if (!$this->inputBoxType($jobParam)) {
                return false;
            }
            if (!$this->defaultValue($jobParam)) {
                return false;
            }
            if (!$this->dataListValidate($jobParam)) {
                return false;
            }
            if (!$this->dataLengthAndDataList($jobParam)) {
                return false;
            }
            if (!$this->dataSizeAndDataList($jobParam)) {
                return false;
            }
            if (!$this->dataListAndDataType($jobParam)) {
                return false;
            }
            if (!$this->dataLengthAndDefault($jobParam)) {
                return false;
            }
            if (!$this->dataSizeAndDefault($jobParam)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function inputBoxNameRequired($jobParam)
    {
        if (empty($jobParam[self::INPUT_BOX_NAME])) {
            $this->error_msg[] = [self::INPUT_BOX_NAME, '输入框名称不能为空'];
            return false;
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataLengthSpecification($jobParam)
    {
        if ($dataConstraint = $jobParam[self::DATA_LENGTH]) {
            if (!$this->constraintSpecification($jobParam)) {
                $this->error_msg[] = [self::DATA_LENGTH, '请填写正确的数据范围约束'];
                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataSizeSpecification($jobParam)
    {
        if ($dataConstraint = $jobParam[self::DATA_SIZE]) {
            if (!$this->constraintSpecification($jobParam)) {
                $this->error_msg[] = [self::DATA_LENGTH, '请填写正确的数据范围约束'];
                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function constraintSpecification($jobParam)
    {
        if ($dataConstraint = $jobParam[self::DATA_SIZE]) {
            preg_match('/^[(,\[]{1}[\d,-∞]+,[\d,+∞]+[),\]]{1}$/', $dataConstraint, $arr);
            if (count($arr) !== 1) {
                return false;
            }
            if ((strpos(ArrayAide::getValue($arr), '[') !== false) && strpos(ArrayAide::getValue($arr), '-∞')) {
                return false;
            }
            if (strpos(ArrayAide::getValue($arr), ']') && strpos(ArrayAide::getValue($arr), '+∞')) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataType($jobParam)
    {
        if (!in_array($jobParam[self::DATA_TYPE], self::dataTypeList())) {
            $this->error_msg[] = [self::DATA_TYPE, '找不到对应的数据类型'];
            return false;
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function inputBoxNameSort($jobParam)
    {
        if ($jobParam[self::INPUT_BOX_SORT] !== '') {
           if (!StringAide::isInteger($jobParam[self::INPUT_BOX_SORT])) {
               $this->error_msg[] = [self::INPUT_BOX_SORT, '排序值必须为整数'];
               return false;
           }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function defaultValue($jobParam)
    {
        if ($jobParam[self::VALUE_DEFAULT] !== '') {
            if ($jobParam[self::DATA_TYPE] == self::DATA_TYPE_INT) {
                if (!StringAide::isInteger($jobParam[self::VALUE_DEFAULT])) {
                    $this->error_msg[] = [self::VALUE_DEFAULT, '默认值必须是所属数据类型'];
                    return false;
                }
            }
            if ($jobParam[self::DATA_TYPE] == self::DATA_TYPE_FLOAT) {
                if (!is_numeric($jobParam[self::VALUE_DEFAULT])) {
                    $this->error_msg[] = [self::VALUE_DEFAULT, '默认值必须是所属数据类型'];
                    return false;
                }
            }
            if ($jobParam[self::DATA_LIST]) {
                if (!in_array($jobParam[self::VALUE_DEFAULT], $jobParam[self::DATA_LIST])) {
                    $this->error_msg[] = [self::VALUE_DEFAULT, '默认值必须在枚举值中'];
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataListAndDataType($jobParam)
    {
        if ($jobParam[self::DATA_LIST]) {
            if ($jobParam[self::DATA_TYPE] == self::DATA_TYPE_INT) {
                return $this->checkDataList($jobParam, self::DATA_TYPE_INT);
            } elseif ($jobParam[self::DATA_TYPE == self::DATA_TYPE_FLOAT]) {
                return $this->checkDataList($jobParam, self::DATA_TYPE_FLOAT);
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @param $type
     * @return bool
     * @throws Exception
     */
    private function checkDataList($jobParam, $type)
    {
        $i = 1;
        foreach ($jobParam[self::DATA_LIST] as $data) {
            if ((($i === 1) && $data === '')) {
                continue;
            }
            if ($type === self::DATA_TYPE_INT) {
                $flag = StringAide::isInteger($data);
            } elseif ($type === self::DATA_TYPE_FLOAT) {
                $flag = is_numeric($data);
            } else {
                throw new Exception('type error');
            }
            if (!$flag) {
                $this->error_msg[] = [self::DATA_LIST, '枚举值中存在不属于对应的数据类型的值'];
                return false;
            }
            $i++;
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    /*private function constraintAndDefault($jobParam)
    {
        if ((($jobParam[self::VALUE_DEFAULT] !== '') && $jobParam[self::DATA_LENGTH])) {
            $constraintString = $jobParam[self::DATA_CONSTRAINT];
            if ($jobParam[self::DATA_TYPE] == self::DATA_TYPE_STRING) {
                $number = StringAide::strLength($jobParam[self::VALUE_DEFAULT]);
            } elseif (in_array($jobParam[self::DATA_TYPE], [self::DATA_TYPE_INT, self::DATA_TYPE_FLOAT])) {
                $number = $jobParam[self::VALUE_DEFAULT];
            } else {
                $this->error_msg[] = [self::DATA_CONSTRAINT, '数据类型或者数据范围有误'];
                return false;
            }
            $res = static::constraintCalculate($number, $jobParam[self::DATA_CONSTRAINT]);
            if ($res['flag'] === false) {
                $consArr = explode(',', $constraintString);
                $leftString = $consArr[0];
                $rightString = $consArr[1];
                $sign = static::getSign($leftString, $rightString);
                if ($jobParam[self::DATA_TYPE] == self::DATA_TYPE_STRING) {
                    try {
                        $msg = static::getConstraintMsg($jobParam[self::VALUE_DEFAULT], $sign, $number, 2);
                        $this->error_msg[] = [self::DATA_CONSTRAINT, $msg];
                    } catch (Exception $e) {
                        $this->error_msg[] = [self::DATA_CONSTRAINT, '数据范围有误'];
                    }
                } else {
                    try {
                        $msg = static::getConstraintMsg($jobParam[self::VALUE_DEFAULT], $sign, $number, 2);
                        $this->error_msg[] = [self::DATA_CONSTRAINT, $msg];
                    } catch (Exception $e) {
                        $this->error_msg[] = [self::DATA_CONSTRAINT, '数据范围有误'];
                    }
                }
                return false;
            }
        }
        return true;
    }*/

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataLengthAndDefault($jobParam)
    {
        if ((($jobParam[self::VALUE_DEFAULT] !== '') && $jobParam[self::DATA_LENGTH])) {
            $constraintString = $jobParam[self::DATA_LENGTH];
            $number = StringAide::strLength($jobParam[self::VALUE_DEFAULT]);

            $res = static::constraintCalculate($number, $constraintString);
            if ($res['flag'] === false) {
                $consArr = explode(',', $constraintString);
                $leftString = $consArr[0];
                $rightString = $consArr[1];
                $sign = static::getSign($leftString, $rightString);
                try {
                    $msg = static::getConstraintMsg($jobParam[self::VALUE_DEFAULT], $sign, $number, 2);
                    $this->error_msg[] = [self::DATA_LENGTH, $msg];
                } catch (Exception $e) {
                    $this->error_msg[] = [self::DATA_LENGTH, '字符长度有误'];
                }

                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataSizeAndDefault($jobParam)
    {
        if ((($jobParam[self::VALUE_DEFAULT] !== '') && $jobParam[self::DATA_SIZE])) {
            $constraintString = $jobParam[self::DATA_SIZE];
            $number = $jobParam[self::VALUE_DEFAULT];

            $res = static::constraintCalculate($number, $jobParam[$constraintString]);
            if ($res['flag'] === false) {
                $consArr = explode(',', $constraintString);
                $leftString = $consArr[0];
                $rightString = $consArr[1];
                $sign = static::getSign($leftString, $rightString);

                try {
                    $msg = static::getConstraintMsg($jobParam[self::VALUE_DEFAULT], $sign, $number);
                    $this->error_msg[] = [self::DATA_SIZE, $msg];
                } catch (Exception $e) {
                    $this->error_msg[] = [self::DATA_SIZE, '数据大小有误'];
                }

                return false;
            }
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataLengthAndDataList($jobParam)
    {
        if ($jobParam[self::DATA_LENGTH] && $jobParam[self::DATA_LIST]) {
            foreach ($jobParam[self::DATA_LIST] as $value) {
                if ($value === '') {
                    if ($jobParam[self::ALLOWED_NULL] == self::ALLOWED_NULL_FALSE) {
                        $this->error_msg[] = [self::DATA_LIST, '已经选择不能为空,数据枚举值中不能出现空值'];
                    } else {
                        continue;
                    }
                }
                $number = StringAide::strLength($value);

                $res = static::constraintCalculate($number, $jobParam[self::DATA_LENGTH]);
                if ($res['flag'] === false) {
                    $this->error_msg[] = [self::DATA_LIST, '数据枚举值必须满足字符长度范围的约束'];
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataSizeAndDataList($jobParam)
    {
        if ($jobParam[self::DATA_SIZE] && $jobParam[self::DATA_LIST]) {
            foreach ($jobParam[self::DATA_LIST] as $value) {
                if ($value === '') {
                    if ($jobParam[self::ALLOWED_NULL] == self::ALLOWED_NULL_FALSE) {
                        $this->error_msg[] = [self::DATA_LIST, '已经选择不能为空,数据枚举值中不能出现空值'];
                    } else {
                        continue;
                    }
                }
                $number = $value;

                $res = static::constraintCalculate($number, $jobParam[self::DATA_LENGTH]);
                if ($res['flag'] === false) {
                    $this->error_msg[] = [self::DATA_LIST, '数据枚举值必须满足数据大小范围的约束'];
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * @param $jobParam
     * @return bool
     */
    private function inputBoxType($jobParam)
    {
        if (!in_array($jobParam[self::INPUT_BOX_TYPE], self::inputBoxTypeList())) {
            $this->error_msg[] = [self::INPUT_BOX_TYPE, '找不到对应表单输入框类型'];
            return false;
        }
        return true;
    }

    /**
     * @param $jobParam
     * @return bool
     */
    private function dataListValidate($jobParam)
    {
        if (in_array($jobParam[self::INPUT_BOX_TYPE], [self::INPUT_BOX_TYPE_RADIO, self::INPUT_BOX_TYPE_SELECT])) {
            if (empty($jobParam[self::DATA_LIST])) {
                $this->error_msg[] = [self::DATA_LIST, '枚举值不能为空'];
                return false;
            }
            if ($jobParam[self::ALLOWED_NULL] == self::ALLOWED_NULL_TRUE) {
                $firstValue = current($jobParam[self::DATA_LIST]);
                if ($firstValue !== '') {
                    $this->error_msg[] = [self::DATA_LIST, '在允许为空时,第一个枚举值必须设置空值,如`,1,2,3或a=>,b=>2`'];
                    return false;
                }
            }
            if ($jobParam[self::VALUE_DEFAULT] !== '') {
                if (!in_array($jobParam[self::VALUE_DEFAULT], $jobParam[self::DATA_LIST])) {
                    $this->error_msg[] = [self::DATA_LIST, '数据枚举值中没有默认值'];
                }
            }
        }
        return true;
    }

    /**
     * @param $comparisonValue
     * @param $constraint
     * @return array
     */
    public static function constraintCalculate($comparisonValue, $constraint)
    {
        $constraintStringList = explode(',', $constraint);
        $leftString = $constraintStringList[0];
        $rightString = $constraintStringList[1];
        if (strpos($leftString, '-∞') !== false) {
            $leftNum = '-∞';
        } else {
            $leftNum= static::findNum($leftString);
        }
        if (strpos($rightString, '+∞') !== false) {
            $rightNum = '+∞';
        } else {
            $rightNum = static::findNum($rightString);
        }
        if ((strpos($leftString, '(') !== false)) {
            if ($leftNum != '-∞') {
                if (!($comparisonValue > $leftNum))
                    return ['flag' => false, 'leftNum' => $leftNum, 'rightNum' => $rightNum];
            }
        } else {
            if (!($comparisonValue >= $leftNum))
                return ['flag' => false, 'leftNum' => $leftNum, 'rightNum' => $rightNum];
        }
        if ((strpos($rightString, ')') !== false)) {
            if ($rightNum != '+∞') {
                if (!($comparisonValue < $rightNum))
                    return ['flag' => false, 'leftNum' => $leftNum, 'rightNum' => $rightNum];
            }
        } else {
            if (!($comparisonValue <= $rightNum))
                return ['flag' => false, 'leftNum' => $leftNum, 'rightNum' => $rightNum];
        }
        return ['flag' => true, 'leftNum' => $leftNum, 'rightNum' => $rightNum];
    }

    /**
     * @param $leftString
     * @param $rightString
     * @return array
     */
    public static function getSign($leftString, $rightString)
    {
        if (strpos($leftString, '(')) {
            $leftSign = (strpos($leftString, '-∞') !== false)? null:'大于';
        } else {
            $leftSign = '大于等于';
        }
        if (strpos($rightString, ']')) {
            $rightSign = (strpos($rightString, '+∞') !== false)? null:'小于';
        } else {
            $rightSign = '小于等于';
        }
        return ['leftSign' => $leftSign, 'rightSign' => $rightSign];
    }

    /**
     * @param $inputName
     * @param $sign
     * @param $number
     * @param int $type
     * @return string
     * @throws Exception
     */
    public static function getConstraintMsg($inputName, $sign, $number, $type = 1)
    {
        if ($type === 1) {
            if ($sign['leftSign'] && $sign['rightSign']) {
                $msg = $inputName . '必须' . $sign['leftSign'] . $number['leftNum'] . $sign['rightSign'] . $number['rightNum'];
            } elseif ($sign['leftSign'] === null && $sign['rightSign']) {
                $msg = $inputName . '必须' . $sign['rightSign'] . $number['rightNum'];
            } elseif ($sign['leftSign'] && $sign['rightSign'] === null) {
                $msg = $inputName . '必须' . $sign['leftSign'] . $number['leftNum'];
            } else {
                throw new Exception('数据约束有误');
            }
        } else {
            if ($sign['leftSign'] && $sign['rightSign']) {
                $msg = $inputName . '的字符串长度必须' . $sign['leftSign'] . $number['leftNum'] . $sign['rightSign'] . $number['rightNum'];
            } elseif ($sign['leftSign'] === null && $sign['rightSign']) {
                $msg = $inputName . '的字符串长度必须' . $sign['rightSign'] . $number['rightNum'];
            } elseif ($sign['leftSign'] && $sign['rightSign'] === null) {
                $msg = $inputName . '的字符串长度必须' . $sign['leftSign'] . $number['leftNum'];
            } else {
                throw new Exception('数据约束有误');
            }
        }
        return $msg;
    }

    /**
     * @param $str
     * @return string
     */
    public static function findNum($str){
        $str=trim($str);
        if(empty($str)){
            return '';
        }
        $temp=['1', '2', '3', '4', '5',' 6', '7', '8', '9', '0', '.',];
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(in_array($str[$i],$temp)){
                $result.=$str[$i];
            }
        }
        return $result;
    }

    /**
     * @param $jobParam
     * @return string
     */
    public static function jobParamJson($jobParam)
    {
        return json_encode($jobParam);
    }
}
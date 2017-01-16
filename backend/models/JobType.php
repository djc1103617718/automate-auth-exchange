<?php

namespace backend\models;

use common\models\JobType as JobTypeCommon;

class JobType extends JobTypeCommon
{
    public function rules(){
        return [
            [['step_symbol', 'job_type_name', 'app_id'], 'required'],
            [['job_type_name'], 'string', 'max' => 32],
            [['app_id','step_symbol'], 'integer'],
            [['job_type_name'], 'unique'],
            [['step_symbol', 'app_id'], 'unique', 'targetAttribute' => ['step_symbol', 'app_id'], 'message' => 'The combination of Step Symbol and App ID has already been taken.'],
        ];
    }


    /**
     * @return array
     */
    public static function idToNameArray()
    {
        $arr = App::find()->select(['app_name', 'app_id'])->indexBy('app_id')->indexBy('app_id')->asArray()->all();
        $idToName = [];
        foreach ($arr as $k => $v) {
            $idToName[$k] = $v['app_name'];
        }
        return $idToName;
    }

    /**
     * @param $app_id
     * @return array
     */
    public static function idToStepSymbolArray($app_id){
        $arr = JobType::find()->select(['job_type_name'])->where(['app_id' => $app_id])->indexBy('step_symbol')->asArray()->column();
        return $arr;
    }

    /**
     * @return bool
     */
    public function virtualDelete()
    {
        $this->status = self::STATUS_DELETE;
        if (!$this->update()) {
            return false;
        }
        return true;
    }
}
<?php

namespace backend\models;

class AppActionStep extends \common\models\AppActionStep
{
    /**
     * @param $id @step_id
     * @return bool
     */
    public static function isExistJobRelationStep($id)
    {
        $appAction = AppAction::findOne(AppActionStep::findOne($id)->action_id);
        $job = Job::find()
            ->where(['job_name' => $appAction->action_name])
            ->andWhere(['not in', 'status', Job::invalidStatus()])
            ->one();
        if (!empty($job)) {
            return true;
        }
        return false;
    }

    /**
     * @param $id @action_id
     * @return bool
     */
    public static function canCreateStep($id)
    {
        $appAction = AppAction::findOne($id);
        $job = Job::find()
            ->where(['job_name' => $appAction->action_name])
            ->andWhere(['not in', 'status', Job::invalidStatus()])
            ->one();
        if (!empty($job)) {
            return false;
        }
        return true;
    }
    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool|int
     */
    /*public function update($runValidation = true, $attributeNames = null)
    {
        if (static::isExistJobRelationStep($this->step_id)) {
            return false;
        }
        return parent::update($runValidation, $attributeNames);
    }*/
}
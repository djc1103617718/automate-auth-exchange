<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;

class AppAction extends \common\models\AppAction
{
    public $app_name;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['app_name', 'safe'];
        return $rules;
    }

    /**
     * @param $attribute
     * @param $params
     */
    /*public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->status == 2) {
                $this->addError($attribute, '请确认父级状态是否已经锁定');
            }
        }
    }*/

    /**
     * 级联修改 app_action.status
     * @return bool
     */
    public function deleteAppAction()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            AppActionStep::updateAll(['status' => AppActionStep::STATUS_LOCKING], ['status' => AppActionStep::STATUS_NORMAL, 'action_id' => $this->action_id]);

            $this->status = self::STATUS_LOCKING;
            if ($this->update() === false) {
                throw new Exception('删除app失败!');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /*public function deleteAppAction()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $actionStep = AppActionStep::find()->where(['action_id' => $this->action_id, 'status' => 1])->all();
        try {
            if (!empty($actionStep)) {
                foreach ($actionStep as $v) {
                    $v->status = 2;
                    if (!$v->update()) {
                        throw new Exception('删除action失败!');
                    }
                };
            }
            $this->status = 2;
            if (!$this->update()) {
                throw new Exception('删除app失败!');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        } catch (StaleObjectException $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }*/

    /**
     * @param $id $action_id
     * @return bool
     */
    public static function isExistJobRelationAppAction($id)
    {
        $actionName = AppAction::findOne($id)->action_name;
        $job = Job::find()
            ->where(['job_name' => $actionName])
            ->andWhere(['not in', 'status', Job::invalidStatus()])
            ->one();
        if (!empty($job)) {
            return true;
        }
        return false;
    }
}
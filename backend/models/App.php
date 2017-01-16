<?php

namespace  backend\models;

use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class App extends \common\models\App
{
    /**
     * 级联删除app(在这里是级联修改status)
     * @return bool
     */
    public function deleteApp()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $appAction = AppAction::find()
            ->select(['app_id', 'action_id', 'status'])
            ->where(['app_id' =>$this->app_id, 'status' => AppAction::STATUS_NORMAL])
            ->asArray()->all();

        try {
            if (!empty($appAction)) {
                $actionIds = ArrayHelper::getColumn($appAction, 'action_id');
                AppActionStep::updateAll(['status' => AppActionStep::STATUS_LOCKING], ['action_id' => $actionIds, 'status' => AppActionStep::STATUS_NORMAL]);
                AppAction::updateAll(['status' => AppAction::STATUS_LOCKING], ['action_id' => $actionIds]);
                $this->status = self::STATUS_LOCKING;
                if ($this->update() === false){
                    throw new Exception('update failure');
                }
                $transaction->commit();
                return true;
            } else {
                $this->status = self::STATUS_LOCKING;
                if ($this->update() === false) {
                    throw new Exception('update failure');
                }
                $transaction->commit();
                return true;
            }

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }

    /*public function deleteApp()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $appAction = AppAction::find()->where(['app_id' =>$this->app_id, 'status' => AppAction::STATUS_NORMAL])->all();
        try {
            if (!empty($appAction)) {
                foreach ($appAction as $v) {
                    $actionStep = AppActionStep::find()
                        ->where(['action_id' => $v->action_id, 'status' => 1])
                        ->all();
                    if (!empty($actionStep)) {
                        foreach ($actionStep as $stepModel) {
                            $stepModel->status = 2;
                            if (!$stepModel->update()) {
                                throw new Exception('删除action_step失败!');
                            };
                        }
                    }
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
        }
        return true;
    }*/



    public static function findOneAppDetail($id, $action_id, $step_id)
    {
        $query = new Query();
        $query->select('*,actions.status as action_status,app.app_id as aid,actions.action_id as cid,
            actions.created_time as action_created_time,
            actions.updated_time as action_updated_time,steps.status as step_status,
            steps.created_time as step_created_time,steps.updated_time as step_updated_time')
            ->from('app')
            ->leftJoin(['actions'=>'app_action'],'actions.app_id=app.app_id')
            ->leftJoin(['steps'=>'app_action_step'], 'steps.action_id=actions.action_id');
        $result = $query->where(['app.app_id' => $id, 'actions.action_id' => $action_id, 'step_id' => $step_id])->one();

        if (empty($result)) {
            throw new NotFoundHttpException('找不到app_id');
        }
        return $result;
    }

    /**
     * @param $id $app_id
     * @return bool
     */
    public static function isExistJobRelationApp($id)
    {
        $actionNameList = AppAction::find()
            ->select(['action_name'])
            ->where(['app_id' => $id, 'status' => AppAction::STATUS_NORMAL])
            ->asArray()
            ->column();
        $job = Job::find()
            ->where(['in', 'job_name', $actionNameList])
            ->andWhere(['not in', 'status', Job::invalidStatus()])
            ->asArray()
            ->one();

        if (!empty($job)) {
            return true;
        }
        return false;
    }
}
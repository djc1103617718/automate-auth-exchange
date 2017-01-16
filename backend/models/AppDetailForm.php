<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class AppDetailForm extends Model
{
    public $app_id;
    public $app_name;
    public $package_name;
    public $action_class_name;
    public $category;
    public $search_name;
    public $action_id;
    public $action_name;
    public $step_id;
    public $step_symbol;
    public $job_param;
    public $status;
    public $action_status;
    public $step_status;

    public function rules()
    {
        return [
            [['app_id', 'app_name', 'package_name', 'search_name', 'category', 'action_class_name'],'required'],
            [['action_id', 'step_id', 'step_symbol','status', 'category', 'action_status', 'step_status'], 'integer'],
            [['action_name', 'job_param'], 'string'],
            [['action_class_name'], 'string', 'max' => 46],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => Yii::t('app', 'AppID'),
            'app_name' => Yii::t('app', 'App名'),
            'package_name' => Yii::t('app', '软件包名'),
            'search_name' => Yii::t('app', '软件搜索名'),
            'action_name' => Yii::t('app', '动作名'),
            'action_class_name' =>  Yii::t('app', '动作价格类名'),
            'category' => Yii::t('app', '分类'),
            'step_symbol' => Yii::t('app', '自动化代号'),
            'job_param' => Yii::t('app', '自动化参数'),
            'status' => Yii::t('app', 'App状态'),
            'action_status' => Yii::t('app', '动作状态'),
            'step_status' => Yii::t('app', '自动化状态'),
        ];
    }

    public static function findOneAppDetail($id, $action_id, $step_id)
    {
        $query = new Query();
        $query->select('*,actions.status as action_status,app.app_id as id,actions.app_id as action_app_id,
            actions.created_time as action_created_time,actions.updated_time as action_updated_time,
            steps.action_id as step_action_id,steps.status as step_status,
            steps.created_time as step_created_time,steps.updated_time as step_updated_time')
            ->from('app')->leftJoin(['actions'=>'app_action'],'actions.app_id=app.app_id')
            ->leftJoin(['steps'=>'app_action_step'], 'steps.action_id=actions.action_id');
        $result = $query->where(['app.app_id' => $id, 'actions.action_id' => $action_id, 'step_id' => $step_id])->one();

        if (empty($result)) {
            throw new NotFoundHttpException('找不到app_id');
        }
        return $result;
    }

    /**
     * 整体更新app
     * @return bool
     */
    public function update()
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $appModel = App::findOne($this->app_id);
            $appModel->app_name = $this->app_name;
            $appModel->package_name = $this->package_name;
            $appModel->search_name = $this->search_name;
            if (!$appModel->validate() || $appModel->update() === false ) {
                throw new Exception('App 更新失败!');
            }

            if ($this->action_id) {
                $appActionModel = AppAction::findOne($this->action_id);
                $appActionModel->app_id = $this->app_id;
                $appActionModel->action_name = $this->action_name;
                $appActionModel->category = $this->category;
                $appActionModel->action_class_name = $this->action_class_name;
                if (!$appActionModel->validate() || $appActionModel->update() === false) {
                    throw new Exception(' App Action 更新失败!');
                }

                if ($this->step_id) {
                    $appActionStepModel = AppActionStep::findOne($this->step_id);
                    $appActionStepModel->action_id = $this->action_id;
                    $appActionStepModel->step_symbol = $this->step_symbol;
                    $appActionStepModel->job_param = $this->job_param;

                    if (!$appActionStepModel->validate() || $appActionStepModel->update() === false) {
                        throw new Exception('App Action Step 更新失败!');
                    }
                }
            }

            $transaction->commit();
            return true;

            } catch (Exception $e) {
                $transaction->rollBack();
                return false;

            } catch (StaleObjectException $e) {
                $transaction->rollBack();
                return false;
            }
    }
}
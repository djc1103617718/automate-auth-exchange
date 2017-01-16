<?php

namespace frontend\user\models;

use Yii;
use frontend\models\AppAction;
use common\components\price\Price;
use yii\base\Exception;
use frontend\models\User;
use frontend\models\FundsRecord;
use common\helper\IdBuilder;
use frontend\models\Job;
use frontend\models\Notice;
use frontend\models\JobDetail;
use frontend\models\AppActionStep;

class TaskTemplateForm extends \common\models\TaskTemplateForm
{
    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate() || !$this->validateJobParams()) {
            return false;
        }
        $user_id = Yii::$app->user->id;
        $appActionModel = AppAction::findOne($this->action_id);
        $action_name = $appActionModel->action_name;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $priceComponent = new Price($appActionModel->action_class_name, $this);
            $this->price = $priceComponent->getPrice();
            if ($this->price != 0 && empty($this->price)){
                $this->addError(self::PRICE_FACTORY, $priceComponent->getErrors());
                throw new Exception($priceComponent->getErrors());
            }

            $userModel = User::findOne($user_id);
            $fundsRemaining = $userModel->funds_remaining;
            $userModel->funds_remaining = $fundsRemaining - $this->price * $this->num;
            if ($userModel->update() === false){
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('user 更新失败!');
            }

            $jobModel = new Job();
            if ($this->expire_time) {
                $this->expire_time = strtotime($this->expire_time);
            }
            $jobModel->setAttributes($this->toArray());
            $jobModel->job_id = IdBuilder::getUniqueId();
            if (!$jobModel->job_id) {
                $this->addError('idBuilder', IdBuilder::$errorMsg);
                throw new Exception(IdBuilder::$errorMsg);
            }
            $jobModel->job_name = $action_name;
            $jobModel->status = Job::STATUS_NEW;
            $jobModel->price_introduction = $priceComponent->getPricesIntroduction();
            $jobModel->user_id = $user_id;
            if (!$jobModel->save()){
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('Job 创建失败!');
            }

            $noticeModel = new Notice();
            $noticeModel->category_name = Notice::CATEGORY_NAME_NEW_JOB;
            $noticeModel->title = '新任务';
            $noticeModel->description = '有新的订单,请尽快审核';
            $noticeModel->content = "<a href='" . Yii::$app->urlManagerBackend->createAbsoluteUrl(['job/view', 'id'=>$jobModel->job_id]) . "' >请点击链接审核</a>";
            if (!$noticeModel->save()) {
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('消息创建失败');
            }

            $fundsRecordModel = new FundsRecord();
            $fundsRecordModel->funds_record_id = IdBuilder::getUniqueId();
            if (!$fundsRecordModel->funds_record_id) {
                $this->addError('idBuilder', IdBuilder::$errorMsg);
                throw new Exception(IdBuilder::$errorMsg);
            }
            $fundsRecordModel->user_id = $user_id;
            $fundsRecordModel->funds_num = $this->price * $this->num;
            $fundsRecordModel->current_balance = $userModel->funds_remaining;
            $fundsRecordModel->record_source = FundsRecord::RECORD_SOURCE_JOB . $jobModel->job_id;
            $fundsRecordModel->record_name = $action_name;
            $fundsRecordModel->type = FundsRecord::TYPE_EXPENSES;
            if (!$fundsRecordModel->save()){
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('funds Record 创建失败!');
            }

            if (!$this->job_params) {
                throw new Exception('缺少自动化执行参数');
            }

            foreach ($this->job_params as $step_id => $stepArgs) {
                $jobDetailModel = new JobDetail();
                $step_symbol = AppActionStep::findOne($step_id)->step_symbol;
                $jobDetailModel->step_symbol = $step_symbol;
                $jobDetailModel->job_id = $jobModel->job_id;
                foreach ($stepArgs as $arg => $value) {
                    $jobDetailModel->$arg = $value;
                }
                if (!$jobDetailModel->save()) {
                    $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                    throw new Exception('Job_detail 创建失败!');
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * 任务草稿
     * @return bool
     */
    public function draftSave(){
        if (!$this->validate() || !$this->validateJobParams()) {
            return false;
        }

        $user_id = Yii::$app->user->id;
        $appActionModel = AppAction::findOne($this->action_id);
        $action_name = $appActionModel->action_name;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $priceComponent = new Price($appActionModel->action_class_name, $this);
            $this->price = $priceComponent->getPrice();
            if ($this->price != 0 && empty($this->price)){
                $this->addError(self::PRICE_FACTORY, $priceComponent->getErrors());
                throw new Exception($priceComponent->getErrors());
            }

            $jobModel = new Job();
            if ($this->expire_time) {
                $this->expire_time = strtotime($this->expire_time);
            }
            $jobModel->setAttributes($this->toArray());
            $jobModel->job_id = IdBuilder::getUniqueId();
            if (!$jobModel->job_id) {
                $this->addError('idBuilder', IdBuilder::$errorMsg);
                throw new Exception(IdBuilder::$errorMsg);
            }
            $jobModel->job_name = $action_name;
            $jobModel->user_id = $user_id;
            $jobModel->price_introduction = $priceComponent->getPricesIntroduction();
            $jobModel->status = Job::STATUS_DRAFT;
            if (!$jobModel->save()){
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('Job 创建失败!');
            }

            if (!$this->job_params) {
                $this->addError('job_params', '缺少自动化执行参数');
                throw new Exception('缺少自动化执行参数');
            }

            foreach ($this->job_params as $step_id => $stepArgs) {
                $jobDetailModel = new JobDetail();
                $step_symbol = AppActionStep::findOne($step_id)->step_symbol;
                $jobDetailModel->step_symbol = $step_symbol;
                $jobDetailModel->job_id = $jobModel->job_id;
                foreach ($stepArgs as $arg => $value) {
                    $jobDetailModel->$arg = $value;
                }
                if (!$jobDetailModel->save()) {
                    $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                    throw new Exception('Job_detail 创建失败!');
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    /**
     * 更新草稿箱任务
     * @return bool
     */
    public function update()
    {
        $jobModel = Job::findOne($this->job_id);
        if ($jobModel == null) {
            $this->addError('job_id', '页面不存在');
            return false;
        }
        $this->action_id = AppAction::findOne(['action_name' => $jobModel->job_name])->action_id;
        $appActionModel = AppAction::findOne($this->action_id);

        if (!$this->validate() || !$this->validateJobParams()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $priceComponent = new Price($appActionModel->action_class_name, $this);
            $this->price = $priceComponent->getPrice();
            if ($this->price != 0 && empty($this->price)){
                $this->addError(self::PRICE_FACTORY, $priceComponent->getErrors());
                throw new Exception($priceComponent->getErrors());
            }

            if ($this->expire_time) {
                $this->expire_time = strtotime($this->expire_time);
            }
            $jobModel->setAttributes($this->toArray());
            $jobModel->price_introduction = $priceComponent->getPricesIntroduction();
            if ($jobModel->update() === false){
                $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                throw new Exception('Job 更新失败!');
            }
            if (!$this->job_params) {
                $this->addError('table', '缺少自动化执行参数');
                throw new Exception('缺少自动化执行参数');
            }
            $jobDetailModels = JobDetail::find()->where(['job_id' => $this->job_id])->all();
            if ($jobDetailModels == []) {
                $this->addError('jobDetail', '页面不存在');
                throw new Exception('页面不存在');
            }
            $job_params = $this->job_params;
            foreach ($jobDetailModels as $jobDetailModel) {
                foreach ($job_params as $step_id => $args) {
                    $step_symbol = AppActionStep::findOne(['status' => AppActionStep::STATUS_NORMAL, 'step_id' => $step_id])->step_symbol;
                    if ($jobDetailModel->step_symbol == $step_symbol) {
                        foreach ($args as $arg => $value) {
                            $jobDetailModel->$arg = $value;
                        }
                        break;
                    }
                }
                if ($jobDetailModel->update() === false){
                    $this->addError('table', self::FAILURE_CREATE_JOB_MSG);
                    throw new Exception('job detail 更新失败!');
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
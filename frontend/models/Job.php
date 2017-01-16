<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use common\helper\IdBuilder;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%job}}".
 *
 * @property integer $job_id
 * @property string $job_name
 * @property integer $insert_time
 *
 * @property JobDetail[] $jobDetails
 */
class Job extends \common\models\Job
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['num', 'price'], 'validateFundsRemaining', 'on' => 'draftToTask'],
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),
            [
                'draftToTask' => ['num', 'price']
            ]
        );
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'job_id' => Yii::t('app', '任务单号'),
            'user_id' => Yii::t('app', '用户ID'),
            'job_name' => Yii::t('app', '任务名称'),
            'price' => Yii::t('app', '价格(元)'),
            'created_time' => Yii::t('app', '创建时间'),
            'updated_time' => Yii::t('app', '更新时间'),
            'status' => Yii::t('app', '任务状态'),
            'num' => Yii::t('app', '任务量'),
            'job_remark' => Yii::t('app', '备注'),
            'finished' => Yii::t('app', '已完成量'),
            'expire_time' => Yii::t('app', '过期时间'),
            'price_rate' => Yii::t('app', '任务速度'),
            'price_introduction' => Yii::t('app', '价格简介'),
        ];
    }

    /**
     * @return bool
     */
    public function draftToTask()
    {
        $this->setScenario('draftToTask');
        $this->status = self::STATUS_NEW;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$this->update()) {
                throw new Exception('Job 更新失败!');
            }

            $userModel = User::findOne(Yii::$app->user->id);
            $userModel->funds_remaining = $userModel->funds_remaining - $this->price * $this->num;
            if ($userModel->funds_remaining < 0 || $userModel->update() === false) {
                throw new Exception('User 更新失败!');
            }

            $fundsRecordModel = new FundsRecord();
            $fundsRecordModel->funds_record_id = IdBuilder::getUniqueId();
            $fundsRecordModel->user_id = $userModel->id;
            $fundsRecordModel->funds_num = $this->price * $this->num;
            $fundsRecordModel->current_balance = $userModel->funds_remaining;
            $fundsRecordModel->record_source = FundsRecord::RECORD_SOURCE_JOB . $this->job_id;
            $fundsRecordModel->record_name = $this->job_name;
            $fundsRecordModel->type = FundsRecord::TYPE_EXPENSES;
            if (!$fundsRecordModel->save()){
                throw new Exception('funds Record 创建失败!');
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
        return true;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public static function deleteDraftAll($user_id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $job = Job::find()
                ->select('job_id')
                ->where(['user_id' => $user_id, 'status' => self::STATUS_DRAFT])
                ->asArray()->column();
            if (empty($job)) {
                return true;
            }
            if (!Job::deleteAll(['in', 'job_id', $job])) {
                throw new Exception('deleteDraftAll error');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}

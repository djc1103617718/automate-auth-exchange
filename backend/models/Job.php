<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use common\helper\IdBuilder;

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
    /**
     * job只有这些状态下的任务使对应的app模版可以修改
     *
     * @return array
     */
    public static function invalidStatus()
    {
        return [
            self::STATUS_DELETE,
            self::STATUS_COMPLETE,
            self::STATUS_CANCEL,
        ];
    }

    /**
     * 审核:确认任务
     * @return bool
     */
    public function ensureJob()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->status = Job::STATUS_AWAITING;
            $this->ensure_time = time();
            if (!$this->update()) {
                throw new Exception('Job更新失败');
            }

            $noticeModel = new Notice();
            $noticeModel->user_id = $this->user_id;
            $noticeModel->category_name = Notice::CATEGORY_NAME_APPROVED;
            $noticeModel->title = '任务通过审核';
            $noticeModel->description = '审核通过,任务等待执行';
            $noticeModel->content = '任务单号:<a href="' . Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/user/job/view', 'id' => $this->job_id]) . '">' . $this->job_id . '</a> 审核通过';
            $noticeModel->status = Notice::STATUS_UNREAD;

            if (!$noticeModel->save()) {
                //var_dump($noticeModel->errors);die;
                throw new Exception('消息记录保存失败');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 审核:取消任务
     * @return bool
     */
    public function cancelJob()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->status = Job::STATUS_CANCEL;
            $this->ensure_time = time();
            if (!$this->update()) {
                throw new Exception('Job 更新失败!');
            }

            $userModel = User::findOne($this->user_id);
            $userModel->funds_remaining = $userModel->funds_remaining + $this->num * $this->price;
            if (!$userModel->update()) {
                throw new Exception('User 更新失败!');
            }

            $recordSource = FundsRecord::RECORD_SOURCE_JOB . $this->job_id;
            $oldFundsRecordModel = FundsRecord::findOne(['record_source' => $recordSource]);
            if (!$oldFundsRecordModel) {
                throw new Exception('没有对应的资金记录');
            }
            $fundsRecordModel = new FundsRecord();
            $fundsRecordModel->record_source = $recordSource;
            $fundsRecordModel->record_name = $oldFundsRecordModel->record_name;
            $fundsRecordModel->funds_num = $oldFundsRecordModel->funds_num;
            $fundsRecordModel->type = FundsRecord::TYPE_REFUND;
            $fundsRecordModel->funds_record_id = IdBuilder::getUniqueId();
            $fundsRecordModel->current_balance = $userModel->funds_remaining;
            $fundsRecordModel->user_id = $this->user_id;
            if (!$fundsRecordModel->save()) {
                throw new Exception('资金记录保存失败');
            }

            $noticeModel = new Notice();
            $noticeModel->user_id = $this->user_id;
            $noticeModel->category_name = Notice::CATEGORY_NAME_REFUND_NOTICE;
            $noticeModel->title = '任务审核失败';
            $noticeModel->description = '任务审核失败,退回款项';
            $noticeModel->content = '任务单号:<a href="' . Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/user/job/view', 'id' => $this->job_id]) . '">' . $this->job_id . '</a>审核未通过;退回金额:' . ($oldFundsRecordModel->funds_num)/100 . '元,<a href="' . Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/user/funds-record/view', 'id' => $fundsRecordModel->funds_record_id]) . '">查看资金详情</a>';
            $noticeModel->status = Notice::STATUS_UNREAD;
            if (!$noticeModel->save()) {
                throw new Exception('消息记录保存失败');
            }

            $transaction->commit();

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}

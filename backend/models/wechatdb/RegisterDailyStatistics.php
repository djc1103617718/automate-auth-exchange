<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;

class RegisterDailyStatistics extends \common\models\wechatdb\RegisterDailyStatistics
{
    public $current_register_num;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['current_register_num', 'safe'];
        return $rules;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['current_register_num'] = Yii::t('app', '当前注册量(当下)');
        return $attributeLabels;
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @return int|string
     */
    public static function dailyRegisterNum($startTime, $endTime, $tableClass)
    {
        $accountNum = $tableClass::find()
            ->where(['status' => UserQihu360Mobile::STATUS_NORMAL])
            ->andWhere(['between', 'regist_time', $startTime, $endTime])
            ->count();
        return $accountNum;
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @return int|string
     */
    public static function dailyLoginNumFor360($startTime, $endTime)
    {
        $sql = "SELECT COUNT(*) FROM (SELECT * FROM devicelog WHERE account <> '' AND account <> NULL AND log_time BETWEEN '$startTime' AND '$endTime' AND job_type = 2002 GROUP BY account) AS loginRecord";
        $loginNum = Yii::$app->weChatDb->createCommand($sql)->queryScalar();
        return $loginNum;
    }

    /**
     * 360 统计
     *
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public static function dailyStatisticsFor360($startTime, $endTime)
    {
        $transaction = \Yii::$app->weChatDb->beginTransaction();
        try {
            $model = new RegisterDailyStatistics();
            $model->register_num = static::dailyRegisterNum($startTime, $endTime, UserQihu360Mobile::className());
            $model->login_num = static::dailyLoginNumFor360($startTime, $endTime);
            $model->app_name = self::APP_NAME_360;
            $model->statistics_time = $endTime;
            $model->created_time = date('Y-m-d H:i:s', time());
            if (!$model->save()) {
                throw new Exception('统计失败!');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }

    /**
     * 壁纸统计
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    public static function dailyStatisticsForBizhi($startTime, $endTime)
    {
        $transaction = \Yii::$app->weChatDb->beginTransaction();
        try {
            $model = new RegisterDailyStatistics();
            $model->register_num = static::dailyRegisterNum($startTime, $endTime, UserXunruibizhi::className());
            //$model->login_num = static::dailyLoginNumFor360($startTime, $endTime);
            $model->app_name = self::APP_NAME_BIZHI;
            $model->statistics_time = $endTime;
            $model->created_time = date('Y-m-d H:i:s', time());
            if (!$model->save()) {
                throw new Exception('统计失败!');
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }

    }
}
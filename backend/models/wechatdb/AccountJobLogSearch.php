<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\AccountJobLog;

/**
 * AccountJobLogSearch represents the model behind the search form about `backend\models\wechatdb\AccountJobLog`.
 */
class AccountJobLogSearch extends AccountJobLog
{
    const APP_NAME_WE_CHAT = 'weixin';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_log_id', 'job_num', 'commission', 'created_time', 'updated_time'], 'integer'],
            [['account', 'app_name', 'job_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public static function searchAttributes()
    {
        return [
            //'金额' => 'funds_num',
            '任务量' => 'job_num',
            //'当前账户余额' => 'current_balance',
            '佣金' => 'commission',
            '账号' => 'account',
            '应用名称' => 'app_name',
            '任务单号' => 'job_id',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AccountJobLog::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'job_log_id' => $this->job_log_id,
            'job_num' => $this->job_num,
            'commission' => $this->commission,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'job_id', $this->job_id]);

        return $dataProvider;
    }

    /**
     * 设备下的微信任务日志
     * @param $accountArray
     * @param $params
     * @return ActiveDataProvider
     */
    public function search2($accountArray, $params)
    {
        $query = AccountJobLog::find()
            ->where(['app_name'=>self::APP_NAME_WE_CHAT])
            ->andWhere(['in', 'account', $accountArray]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'job_log_id' => $this->job_log_id,
            'job_num' => $this->job_num,
            'commission' => $this->commission,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'job_id', $this->job_id]);

        return $dataProvider;
    }
}

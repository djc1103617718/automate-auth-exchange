<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\DeviceLog;

/**
 * DeviceLogSearch represents the model behind the search form about `backend\models\wechatdb\DeviceLog`.
 */
class DeviceLogSearch extends DeviceLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logid', 'job_type', 'status', 'final'], 'integer'],
            [['deviceid', 'app_name', 'account', 'log_time', 'jobid', 'params'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            '设备ID' => 'deviceid',
            '执行时间' => 'log_time',
            '应用名称' => 'app_name',
            '账号' => 'account',
            '任务ID' => 'jobid'
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DeviceLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'log_time' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'logid' => $this->logid,
            'job_type' => $this->job_type,
            'status' => $this->status,
            'final' => $this->final,
        ]);

        $query->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'jobid', $this->jobid])
            ->andFilterWhere(['like', 'log_time', $this->log_time])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}

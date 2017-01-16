<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\WeChatManageLog;

/**
 * WeChatManageLogSearch represents the model behind the search form about `backend\models\wechatdb\WeChatManageLog`.
 */
class WeChatManageLogSearch extends WeChatManageLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logid', 'job_type', 'status'], 'integer'],
            [['deviceid', 'account', 'log_time', 'jobid', 'params'], 'safe'],
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
            '自动化代号' => 'job_type',
            '设备ID' => 'deviceid',
            '微信账号' => 'account',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $id
     * @param array $params
     * @return ActiveDataProvider
     * @internal param $wechatList
     */
    public function search($id ,$params)
    {
        $query = WeChatManageLog::find()->where(['deviceid' => $id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'log_time' => SORT_DESC,
                ]
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
            'log_time' => $this->log_time,
            'job_type' => $this->job_type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'jobid', $this->jobid])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}

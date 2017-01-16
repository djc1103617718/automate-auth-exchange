<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\WechatOnlineTimeLog;

/**
 * WechatOnlineTimeLogSearch represents the model behind the search form about `backend\models\wechatdb\WechatOnlineTimeLog`.
 */
class WechatOnlineTimeLogSearch extends WechatOnlineTimeLog
{
    const SEARCH_TYPE_DEFAULT = 'default';
    const SEARCH_TYPE_ACCOUNT = 'account';
    const SEARCH_TYPE_DEVICE = 'device';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'online_time', 'created_time', 'statistics_time'], 'integer'],
            [['account', 'device_id'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            '微信账号' => 'account',
            '设备ID' => 'device_id'
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
        $query = $this->searchQueryInit(self::SEARCH_TYPE_DEFAULT);
        return $this->searchExecute($query, $params);
    }

    public function searchAccount($account, $params)
    {
        $query = $this->searchQueryInit(self::SEARCH_TYPE_ACCOUNT, ['account' => $account]);
        return $this->searchExecute($query, $params);
    }

    public function searchDevice($device_id, $params)
    {
        $query = $this->searchQueryInit(self::SEARCH_TYPE_DEVICE, ['device_id' => $device_id]);
        return $this->searchExecute($query, $params);
    }

    /**
     * @param $type
     * @param null $initParam
     * @return $this|\yii\db\ActiveQuery
     * @throws Exception
     */
    private function searchQueryInit($type, $initParam = null)
    {
        if ($type == self::SEARCH_TYPE_DEFAULT) {
            $query = WechatOnlineTimeLog::find();
        } elseif ($type == self::SEARCH_TYPE_ACCOUNT) {
            $query = WechatOnlineTimeLog::find()->where(['account' => $initParam['account']]);
        } elseif ($type == self::SEARCH_TYPE_DEVICE) {
            $query = WechatOnlineTimeLog::find()->where(['device_id' => $initParam['device_id']]);
        } else {
            throw new Exception('TYPE ERROR');
        }
        return $query;
    }

    /**
     * @param $query
     * @param $params
     * @return ActiveDataProvider
     */
    private function searchExecute($query, $params)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'statistics_time' => SORT_DESC,
                    'created_time' => SORT_DESC,
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
            'log_id' => $this->log_id,
            'online_time' => $this->online_time,
            'created_time' => $this->created_time,
            'statistics_time' => $this->statistics_time,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'device_id', $this->device_id]);

        return $dataProvider;
    }
}

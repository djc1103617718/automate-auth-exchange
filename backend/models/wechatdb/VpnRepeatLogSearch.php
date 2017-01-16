<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\VpnRepeatLog;

/**
 * VpnRepeatLogSearch represents the model behind the search form about `backend\models\wechatDb\VpnRepeatLog`.
 */
class VpnRepeatLogSearch extends VpnRepeatLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'vpn_id', 'city', 'statistics_time', 'created_time'], 'integer'],
            [['vpn_name', 'vpn_ip', 'username', 'password'], 'safe'],
            [['repetition_rate'], 'number'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            'VPN ID' => 'vpn_id',
            'VPN名' => 'vpn_name',
            'VPN IP' => 'vpn_ip',
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
        $query = VpnRepeatLog::find();

        // add conditions that should always apply here

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
            'repetition_rate' => $this->repetition_rate,
            'vpn_id' => $this->vpn_id,
            'city' => $this->city,
            'statistics_time' => $this->statistics_time,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'vpn_name', $this->vpn_name])
            ->andFilterWhere(['like', 'vpn_ip', $this->vpn_ip])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
}

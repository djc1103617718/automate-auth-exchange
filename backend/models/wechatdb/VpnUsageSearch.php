<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\VpnUsage;

/**
 * VpnUsageSearch represents the model behind the search form about `backend\models\wechatdb\VpnUsage`.
 */
class VpnUsageSearch extends VpnUsage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nouseid', 'vpnid'], 'integer'],
            [['deviceid', 'ipaddr', 'access_time'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            'VPN ID' => 'vpn_id',
            'VPN名' => 'vpn_name',
            'VPN IP' => 'vpn_ip',
            '设备ID' => 'deviceid',
            'IP地址' => 'ipaddr',
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
        $query = VpnUsage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'access_time' => SORT_DESC,
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
            'nouseid' => $this->nouseid,
            'access_time' => $this->access_time,
            'vpnid' => $this->vpnid,
        ]);

        $query->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'ipaddr', $this->ipaddr]);

        return $dataProvider;
    }
}

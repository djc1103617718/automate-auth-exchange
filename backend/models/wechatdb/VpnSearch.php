<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\Vpn;

/**
 * VpnSearch represents the model behind the search form about `backend\models\wechatdb\Vpn`.
 */
class VpnSearch extends Vpn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vpnid', 'city', 'used'], 'integer'],
            [['vpnname', 'vpnip', 'username', 'password'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            'VPN ID' => 'vpnid',
            'VPN名' => 'vpnname',
            'VPN IP' => 'vpnip',
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
        $query = Vpn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vpnid' => $this->vpnid,
            'city' => $this->city,
            'used' => $this->used,
        ]);

        $query->andFilterWhere(['like', 'vpnname', $this->vpnname])
            ->andFilterWhere(['like', 'vpnip', $this->vpnip])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Vip;

/**
 * VipSearch represents the model behind the search form about `backend\models\Vip`.
 */
class VipSearch extends Vip
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'created_time', 'updated_time'], 'integer'],
            [['vip_name', 'description'], 'safe'],
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
            'VIP ID' => 'vip_id',
            'VIP名称' => 'vip_name',
            '描叙' => 'description',
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
        $query = Vip::find();

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
            'vip_id' => $this->vip_id,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'vip_name', $this->vip_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

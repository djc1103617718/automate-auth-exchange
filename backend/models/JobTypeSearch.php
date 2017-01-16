<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JobType;

/**
 * JobTypeSearch represents the model behind the search form about `backend\models\JobType`.
 */
class JobTypeSearch extends JobType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'step_symbol', 'app_id'], 'integer'],
            [['job_type_name', 'status'], 'safe']
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
            '自动化代号' => 'step_symbol',
            '代号名称' => 'job_type_name'
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
        $query = JobType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
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
            'type_id' => $this->type_id,
            'step_symbol' => $this->step_symbol,
            'app_id' => $this->app_id,
        ]);

        $query->andFilterWhere(['like', 'job_type_name', $this->job_type_name]);

        return $dataProvider;
    }
}

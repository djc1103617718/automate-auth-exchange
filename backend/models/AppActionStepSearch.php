<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AppActionStep;

/**
 * AppActionStepSearch represents the model behind the search form about `backend\models\AppActionStep`.
 */
class AppActionStepSearch extends AppActionStep
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['step_id', 'action_id', 'step_symbol', 'status', 'created_time', 'updated_time'], 'integer'],
            [['job_param'], 'safe'],
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
            //'job_param' => 'job_param',
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
        $query = AppActionStep::find();

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
            'step_id' => $this->step_id,
            'action_id' => $this->action_id,
            'step_symbol' => $this->step_symbol,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'job_param', $this->job_param]);

        return $dataProvider;
    }
}

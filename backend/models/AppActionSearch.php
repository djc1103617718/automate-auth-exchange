<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AppAction;

/**
 * AppActionSearch represents the model behind the search form about `backend\models\AppAction`.
 */
class AppActionSearch extends AppAction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_id', 'app_id'], 'integer'],
            [['category','action_class_name' , 'status', 'action_name'], 'safe'],
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
            '动作类型' => 'category',
            '动作价格类名' => 'action_class_name',
            '动作名称' => 'action_name'
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
        $query = AppAction::find();

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
            'action_id' => $this->action_id,
            'app_id' => $this->app_id,
            'status' => $this->status,
            'category' => $this->category,
        ]);

        $query->andFilterWhere(['like', 'action_name', $this->action_name])
            ->andFilterWhere(['like', 'action_class_name', $this->action_class_name]);

        return $dataProvider;
    }

}

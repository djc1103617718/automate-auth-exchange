<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * AppSearch represents the model behind the search form about `common\models\App`.
 */
class AppSearch extends App
{
    public $action_name;
    public $action_status;
    public $action_id;
    public $step_id;
    public $step_symbol;
    public $step_status;
    public $job_param;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['app_name', 'package_name', 'search_name', 'status', 'action_name', 'action_status','action_id'], 'safe'],
            [['step_id', 'step_symbol', 'step_status', 'job_param'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            'App名称' => 'app_name',
            'App软件包名' => 'package_name',
            'App可搜索名' => 'search_name',
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function detailSearch($params)
    {
        $query = new Query();
        $query->select('app.*,actions.action_id,actions.action_name,actions.category,actions.action_class_name,actions.status as action_status,steps.step_id,steps.step_symbol,steps.status as step_status,steps.job_param')
            ->from('app')->leftJoin(['actions'=>'app_action'],'actions.app_id=app.app_id')
            ->leftJoin(['steps'=>'app_action_step'], 'steps.action_id=actions.action_id');

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
            'app.app_id' => $this->app_id,
            'status' => $this->status,
            'actions.action_id' => $this->action_id,
            'actions.status' => $this->action_status,
            'steps.step_id' => $this->step_id,
            'steps.step_symbol' => $this->step_symbol,
            'steps.status' => $this->step_status,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'actions.action_name', $this->action_name])
            ->andFilterWhere(['like', 'steps.job_param', $this->job_param])
            ->andFilterWhere(['like', 'search_name', $this->search_name]);

        return $dataProvider;
    }

    public function search($params)
    {
        $query = App::find();

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
            'app_id' => $this->app_id,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'app_name', $this->app_name])
            ->andFilterWhere(['like', 'package_name', $this->package_name])
            ->andFilterWhere(['like', 'search_name', $this->search_name]);

        return $dataProvider;
    }
}

<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\RegisterDailyStatistics;

/**
 * DailyStatisticsFor360Search represents the model behind the search form about `backend\models\wechatdb\DailyStatisticsFor360`.
 */
class RegisterDailyStatisticsSearch extends RegisterDailyStatistics
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'register_num', 'login_num', 'statistics_time', 'created_time'], 'integer'],
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
            '应用名称' => 'app_name',
            '注册量' => 'register_num',
            '登录量' => 'login_num',
            '统计时间' => 'statistics_time',
            '创建时间' => 'created_time',
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
        $query = RegisterDailyStatistics::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'statistics_time' => SORT_DESC,
                    'created_time' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'register_num' => $this->register_num,
            'login_num' => $this->login_num,
            'statistics_time' => $this->statistics_time,
            'created_time' => $this->created_time,
        ]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JobSearch represents the model behind the search form about `backend\models\Job`.
 */
class JobSearch extends Job
{
    public $username;
    public $shop_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_time', 'updated_time', 'expire_time', 'finished'], 'integer'],
            ['job_id', 'string'],
            [['username', 'price', 'status', 'num', 'job_name', 'shop_name', 'job_remark'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public static function searchAttributes()
    {
        return [
            '任务单号' => 'job_id',
            '用户名' => 'username',
            '商户名' => 'shop_name',
            '任务名称' => 'job_name',
            '任务量' => 'num',
            '备注' => 'job_remark',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @param null $status
     * @return ActiveDataProvider
     */

    public function searchDetail($params, $status = null)
    {
        if ($status === null) {
            $query = Job::find();
        } else {
            $query = Job::find()->where(['job.status' => $status]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->joinWith('user'),
                'pagination' => [
                    'pageSize' => 10,
                ],
            'sort' => [
                'defaultOrder' => [
                    'created_time' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'job_id' => $this->job_id,
            'created_time' => $this->created_time,
            'finished' => $this->finished,
            'expire_time' => $this->expire_time,
            'updated_time' => $this->updated_time,
            'num' => $this->num,
            'price' => empty($this->price) ? null : $this->price/100,
        ]);
        $query->andFilterWhere(['like', 'job_name', $this->job_name]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'user.shop_name', $this->shop_name]);
        $query->andFilterWhere(['like', 'job_remark', $this->job_remark]);

        return $dataProvider;
    }

    /**
     * @param $array
     * @param null $status
     * @return ActiveDataProvider
     */
    public function searchForExport($array, $status=null)
    {
        if ($status == null) {
            $query = Job::find();
        } else {
            $query = Job::find()->where(['job.status' => $status]);
        }
        if ($array) {
            $query = $query->andWhere(['in', 'job_id', $array]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->joinWith('user'),
            'pagination' => [
                'pageSize' => false,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_time' => SORT_DESC,
                ]
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'job_id' => $this->job_id,
            'finished' => $this->finished,
            'num' => $this->num,
            'price' => empty($this->price) ? null : $this->price/100,
        ]);
        $query->andFilterWhere(['like', 'job_name', $this->job_name]);
        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'user.shop_name', $this->shop_name]);
        $query->andFilterWhere(['like', 'job_remark', $this->job_remark]);

        return $dataProvider;
    }
}

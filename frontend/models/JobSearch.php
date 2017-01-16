<?php

namespace frontend\models;

use Composer\IO\NullIO;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JobSearch represents the model behind the search form about `frontend\models\Job`.
 */
class JobSearch extends Job
{
    //const JOB_HAS_EXPIRED = 100;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'job_name', 'job_remark', 'price_introduction'], 'string'],
            [['user_id', 'price' ,'created_time', 'updated_time', 'status', 'num', 'finished', 'expire_time'], 'integer'],
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
            //'价格' => 'price',
            '任务量' => 'num',
            '备注' => 'job_remark',
            '任务名称' => 'job_name',
            '价格简介' => 'price_introduction'
        ];
    }

    public function search($params, $status = null)
    {
        if ($status === null) {
            $query = Job::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['<>', 'status', self::STATUS_DELETE]);
        } else {
            $query = Job::find()->where(['status' => $status, 'user_id' => Yii::$app->user->id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        /*if ($this->price !== '') {
            $this->price = $this->price*100;
        }*/
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
            'status' => $this->status,
            'num' => $this->num,
            //'price' => $this->price,
            'finished' => $this->finished,
            'ensure_time' => $this->ensure_time,
            'expire_time' => $this->expire_time,
        ]);

        $query->andFilterWhere(['like', 'job_id', $this->job_id])
            ->andFilterWhere(['like', 'job_name', $this->job_name])
            ->andFilterWhere(['like', 'price_introduction', $this->price_introduction])
            ->andFilterWhere(['like', 'price_rate', $this->price_rate])
            ->andFilterWhere(['like', 'job_remark', $this->job_remark]);

        return $dataProvider;
    }
}

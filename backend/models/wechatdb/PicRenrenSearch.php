<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\PicRenren;

/**
 * PicRenrenSearch represents the model behind the search form about `backend\models\wechatdb\PicRenren`.
 */
class PicRenrenSearch extends PicRenren
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['picid', 'status'], 'integer'],
            [['user_id', 'name', 'gender', 'pic', 'date'], 'safe'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            '用户名' => 'name',
            '性别' => 'gender',
            '标签' => 'album_mark',
            '创建时间' => 'date',
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
     * @param null $status
     * @return ActiveDataProvider
     */
    public function search($params, $status = null)
    {
        if ($status === null) {
            $query = PicRenren::find();
        } else {
            $query = PicRenren::find()->where(['status' => $status]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
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
            'picid' => $this->picid,
            'status' => $this->status,
            //'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }
}

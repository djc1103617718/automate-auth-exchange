<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\wechatdb\ContentWeibo;

/**
 * ContentWeiboSearch represents the model behind the search form about `backend\models\wechatdb\ContentWeibo`.
 */
class ContentWeiboSearch extends ContentWeibo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contentid', 'status'], 'integer'],
            [['user_id', 'content', 'pic', 'gender', 'keyword', 'date', 'person_mark'], 'safe'],
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
            '性别' => 'gender',
            '关键词' => 'keyword',
            '发布者个性标签' => 'person_mark',
            '内容' => 'content',
            '创建时间' => 'date',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $status = null)
    {
        if ($status === null) {
            $query = ContentWeibo::find();
        } else {
            $query = ContentWeibo::find()->where(['status' => $status]);
        }

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
            'contentid' => $this->contentid,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'person_mark', $this->person_mark]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Notice;

/**
 * NoticeSearch represents the model behind the search form about `backend\models\Notice`.
 */
class NoticeSearch extends Notice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice_id', 'user_id', 'status', 'created_time', 'updated_time'], 'integer'],
            [['category_name', 'title', 'description', 'content'], 'safe'],
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
            '类别' => 'category_name',
            '标题' => 'title',
            '描叙' => 'description'
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param null|int $status @ 1为未读
     * @return ActiveDataProvider
     */
    public function search($params, $status = null)
    {
        $serverNoticeCategory = static::getServerNoticeCategory();

        $query = Notice::find();
        if ($status) {
            $query->where(['status' => self::STATUS_UNREAD])->andWhere(['category_name' => $serverNoticeCategory]);
        } else {
            $query->where(['<>', 'status', self::STATUS_DELETE])->andWhere(['category_name' => $serverNoticeCategory]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_time'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'notice_id' => $this->notice_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

}

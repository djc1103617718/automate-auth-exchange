<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WeChatSearch represents the model behind the search form about `backend\models\wechatdb\WeChat`.
 */
class WeChatSearch extends WeChat
{
    public $wechatNumForDevice;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wechatid'], 'integer'],
            [['account', 'phone', 'gender', 'nickname', 'password', 'province', 'headimg', 'regist_time', 'city', 'regist_source', 'deviceid', 'extra_field', 'wechatNumForDevice'], 'safe'],
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
            '账号' => 'account',
            '手机号' => 'phone',
            '呢称' => 'nickname'
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
        $query = WeChat::find()->with('cityName');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wechatid' => $this->wechatid,
            'regist_time' => $this->regist_time,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'headimg', $this->headimg])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'regist_source', $this->regist_source])
            ->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'extra_field', $this->extra_field]);

        return $dataProvider;
    }

    public function search2($id, $params)
    {
        $query = WeChat::find()->where(['deviceid' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'wechatid' => $this->wechatid,
            'regist_time' => $this->regist_time,
        ]);

        $query->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'headimg', $this->headimg])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'regist_source', $this->regist_source])
            ->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'extra_field', $this->extra_field]);

        return $dataProvider;
    }
}

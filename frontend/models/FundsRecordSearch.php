<?php

namespace frontend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\FundsRecord;

/**
 * FundsRecordSearch represents the model behind the search form about `frontend\models\FundsRecord`.
 */
class FundsRecordSearch extends FundsRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['funds_num', 'user_id', 'type', 'status', 'created_time', 'updated_time', 'current_balance'], 'integer'],
            [['record_name'], 'safe'],
            ['funds_record_id', 'string'],
        ];
    }

    public static function searchAttributes()
    {
        return [
            //'金额' => 'funds_num',
            '记录类别' => 'type',
            //'当前账户余额' => 'current_balance',
            '记录号' => 'funds_record_id',
            '消费类型' => 'record_name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FundsRecord::find()->where(['<>', 'status', self::STATUS_DELETE]);

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
        if (trim($this->type) == '消费') {
            $this->type = self::TYPE_EXPENSES;
        } elseif (trim($this->type) == '支出') {
            $this->type = self::TYPE_RECHARGE;
        } elseif (trim($this->type) == '退款') {
            $this->type = self::TYPE_REFUND;
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'funds_num' => $this->funds_num,
            'current_balance' => $this->current_balance,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'status' => $this->status,
            'created_time' => $this->created_time,
            'updated_time' => $this->updated_time,
        ]);

        $query->andFilterWhere(['like', 'funds_record_id', $this->funds_record_id])
            ->andFilterWhere(['like', 'record_name', $this->record_name])
            ->andFilterWhere(['like', 'record_source', $this->record_source]);

        return $dataProvider;
    }
}

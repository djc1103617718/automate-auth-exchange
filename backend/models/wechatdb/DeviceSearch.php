<?php

namespace backend\models\wechatdb;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


/**
 * DeviceSearch represents the model behind the search form about `backend\models\wechatdb\Device`.
 */
class DeviceSearch extends Device
{
    const DEVICE_TYPE_IOS = 'ios';
    const DEVICE_TYPE_ANDROID = 'android';

    public $num;

    public $searchName = 'DeviceSearch';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nouseid', 'city', 'last_job_type', 'vpnid'], 'integer'],
            [['deviceid', 'last_connect_time', 'province', 'last_job_param', 'account', 'wechat', 'num'], 'safe'],
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
            '设备ID' => 'deviceid',
            //'最后执行自动化代号' => 'last_job_type',
            '运行的微信账号' => 'wechat',
            '最后连接时间' => 'last_connect_time',
        ];
    }

    public static function searchAttributes2()
    {
        return [
            '设备ID' => 'deviceid',
            //'最后执行自动化代号' => 'last_job_type',
            //'运行的微信账号' => 'wechat',
            '最后连接时间' => 'last_connect_time',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @param null $deviceType
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search($params, $deviceType = null)
    {
        if ($deviceType) {
            if ($deviceType === self::DEVICE_TYPE_IOS) {
                $query = Device::find()->where(['>=', 'deviceid', 10000]);
            } elseif ($deviceType === self::DEVICE_TYPE_ANDROID) {
                $query = Device::find()->where(['<', 'deviceid', 10000]);
            } else {
                throw new Exception('params deviceType error');
            }
        } else {
            $query = Device::find();
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'last_connect_time' => SORT_DESC,
                ]
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
            'nouseid' => $this->nouseid,
            'last_connect_time' => $this->last_connect_time,
            'city' => $this->city,
            'last_job_type' => $this->last_job_type,
            'vpnid' => $this->vpnid,
        ]);

        $query->andFilterWhere(['like', 'deviceid', $this->deviceid])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'last_job_param', $this->last_job_param])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'wechat', $this->wechat]);

        return $dataProvider;
    }

    /**
     * 微信设备
     *
     * @param $params
     * @return SqlDataProvider
     */
    public function search2($params)
    {
        if($params) {
            $this->load($params);
        }
        $countSql = 'SELECT COUNT(*) FROM wechat WHERE deviceid IS NOT NULL GROUP BY deviceid';
        $searchSql = 'SELECT COUNT(*) AS num, d.* FROM devices AS d RIGHT JOIN wechat AS w ON d.deviceid = w.deviceid %s GROUP BY w.deviceid HAVING w.deviceid IS NOT NULL';
        $andWhere = '';
        if ($this->validate()) {
            if ($this->deviceid) {
                $andWhere .= "WHERE d.deviceid LIKE '$this->deviceid%'";
            } elseif ($this->last_connect_time) {
                $andWhere .= "WHERE d.last_connect_time LIKE '$this->last_connect_time%'";
            } elseif ($this->wechat) {
                $andWhere .= "WHERE d.wechat LIKE '%$this->wechat%'";
            }
        }
        $countSql = str_replace('%s', $andWhere, $countSql);
        $searchSql = str_replace('%s', $andWhere, $searchSql);
        $count = Yii::$app->weChatDb->createCommand($countSql)->execute();

        $dataProvider = new SqlDataProvider([
            'sql' => $searchSql,
            'params' => [':status' => 1],
            'totalCount' => $count,

            'sort' => [
                'attributes' => [
                    'last_connect_time' => [
                        'asc' => ['last_connect_time' => SORT_ASC],
                        'desc' => ['last_connect_time' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'num' => [
                        'asc' => ['num' => SORT_ASC],
                        'desc' => ['num' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],

                ],
                'defaultOrder' => [
                    'last_connect_time' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $dataProvider->db = Yii::$app->weChatDb;

        return $dataProvider;
    }
}

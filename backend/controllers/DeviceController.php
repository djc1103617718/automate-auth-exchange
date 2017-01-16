<?php

namespace backend\controllers;

use backend\models\wechatdb\City;
use Yii;
use backend\models\wechatdb\WeChatManageLogSearch;
use backend\models\wechatdb\Device;
use backend\models\wechatdb\DeviceSearch;
use backend\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\wechatdb\AccountJobLogSearch;
use backend\models\wechatdb\WeChat;
use backend\models\wechatdb\WeChatSearch;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionDeviceIndex()
    {
        $searchModel = new DeviceSearch();
        $params = Yii::$app->request->queryParams;
        if (isset($params[$searchModel->searchName])) {
            $searchAttribute = key($params[$searchModel->searchName]);
        } else {
            $searchAttribute = null;
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('device-index', [
            'dataProvider' => $dataProvider,
            'searchAttribute' => $searchAttribute,
            'url' => 'device-index',
            'title' => '所有设备',
        ]);
    }

    /**
     * @return string
     */
    public function actionIosDeviceIndex()
    {
        $searchModel = new DeviceSearch();
        $params = Yii::$app->request->queryParams;
        if (isset($params[$searchModel->searchName])) {
            $searchAttribute = key($params[$searchModel->searchName]);
        } else {
            $searchAttribute = null;
        }

        $dataProvider = $searchModel->search($params, $searchModel::DEVICE_TYPE_IOS);

        return $this->render('device-index', [
            'dataProvider' => $dataProvider,
            'searchAttribute' => $searchAttribute,
            'url' => 'ios-device-index',
            'title' => 'IOS设备',
        ]);
    }

    /**
     * @return string
     */
    public function actionAndroidDeviceIndex()
    {
        $searchModel = new DeviceSearch();
        $params = Yii::$app->request->queryParams;
        if (isset($params[$searchModel->searchName])) {
            $searchAttribute = key($params[$searchModel->searchName]);
        } else {
            $searchAttribute = null;
        }

        $dataProvider = $searchModel->search($params, $searchModel::DEVICE_TYPE_ANDROID);

        return $this->render('device-index', [
            'dataProvider' => $dataProvider,
            'searchAttribute' => $searchAttribute,
            'url' => 'android-device-index',
            'title' => 'Android设备',
        ]);
    }

    /**
     * 微信设备
     *
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 设备下的微信账号
     * Displays a single Device model.
     * @param integer $id
     * @return mixed
     */
    public function actionDeviceWechatView($id)
    {
        $device = $this->findModel($id);
        $device_id = $device->deviceid;
        $searchModel = new WeChatSearch();
        $dataProvider = $searchModel->search2($device_id, Yii::$app->request->queryParams);

        return $this->render('device-wechat-view', [
            'dataProvider' => $dataProvider,
            'device' => $device,
        ]);
    }

    /**
     * 设备下的微信任务日志
     * @param $id
     * @return string
     */
    public function actionWechatJobLog($id)
    {
        $device = $this->findModel($id);
        $device_id = $device->deviceid;
        $weChatList = WeChat::find()->select(['phone'])->where(['deviceid' => $device_id])->asArray()->all();
        $weChatList = ArrayHelper::getColumn($weChatList, 'phone');
        $searchModel = new AccountJobLogSearch();
        $dataProvider = $searchModel->search2($weChatList, Yii::$app->request->queryParams);

        return $this->render('wechat-job-log', [
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);
    }

    /**
     * 微信在线时长日志
     *
     * @param $id
     * @return string
     */
    public function actionWechatMaintainLog($id)
    {
        $device = $this->findModel($id);
        $device_id = $device->deviceid;
        $searchModel = new WeChatManageLogSearch();
        $dataProvider = $searchModel->search($device_id, Yii::$app->request->queryParams);

        return $this->render('we-chat-maintain-l,og', [
            'dataProvider' => $dataProvider,
            'id' => $id,
            'device' => $device,
        ]);
    }

    public function actionUpdateVpn($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->nouseid]);
        } else {
            return $this->render('update-vpn', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays a single Device model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Device model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Device the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Device::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

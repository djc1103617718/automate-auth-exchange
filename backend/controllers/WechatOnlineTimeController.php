<?php

namespace backend\controllers;

use backend\models\wechatdb\Device;
use Yii;
use backend\models\wechatdb\WechatOnlineTimeLog;
use backend\models\wechatdb\WechatOnlineTimeLogSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WechatOnlineTimeController implements the CRUD actions for WechatOnlineTimeLog model.
 */
class WechatOnlineTimeController extends BaseController
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
     * Lists all WechatOnlineTimeLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WechatOnlineTimeLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAccountIndex($account)
    {
        $searchModel = new WechatOnlineTimeLogSearch();
        $dataProvider = $searchModel->searchAccount($account ,Yii::$app->request->queryParams);

        return $this->render('account-index', [
            'account' => $account,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeviceIndex($id)
    {
        $deviceModel = Device::findOne($id);
        $searchModel = new WechatOnlineTimeLogSearch();
        $dataProvider = $searchModel->searchDevice($deviceModel->deviceid, Yii::$app->request->queryParams);

        return $this->render('device-index', [
            'id' => $id,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WechatOnlineTimeLog model.
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
     * Deletes an existing WechatOnlineTimeLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WechatOnlineTimeLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WechatOnlineTimeLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WechatOnlineTimeLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

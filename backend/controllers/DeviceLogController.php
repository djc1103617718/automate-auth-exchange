<?php

namespace backend\controllers;

use Yii;
use backend\models\wechatdb\DeviceLog;
use backend\models\wechatdb\DeviceLogSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeviceLogController implements the CRUD actions for DeviceLog model.
 */
class DeviceLogController extends BaseController
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
     * Lists all DeviceLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeviceLog model.
     * @param integer $logid
     * @param string $log_time
     * @return mixed
     */
    public function actionView($logid, $log_time)
    {
        return $this->render('view', [
            'model' => $this->findModel($logid, $log_time),
        ]);
    }

    /**
     * Finds the DeviceLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $logid
     * @param string $log_time
     * @return DeviceLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($logid, $log_time)
    {
        if (($model = DeviceLog::findOne(['logid' => $logid, 'log_time' => $log_time])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\wechatdb\UserQihu360Mobile;
use backend\models\wechatdb\UserXunruibizhi;
use backend\models\wechatdb\RegisterDailyStatistics;
use backend\models\wechatdb\RegisterDailyStatisticsSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DailyStatisticsFor360Controller implements the CRUD actions for DailyStatisticsFor360 model.
 */
class RegisterDailyStatisticsController extends BaseController
{

    const TITLE_360 = '360当前注册情况';
    const TITLE_BIZHI = '统一壁纸当前注册情况';

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
     * Lists all RegisterDailyStatistics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegisterDailyStatisticsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCurrent360()
    {
        $startTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d')));
        $endTime = date('Y-m-d H:i:s', time());
        $loginNum = RegisterDailyStatistics::dailyLoginNumFor360($startTime, $endTime);
        $registerNum = RegisterDailyStatistics::dailyRegisterNum($startTime, $endTime, UserQihu360Mobile::className());
        return $this->render('current-view',[
            'loginNum' => $loginNum,
            'registerNum' => $registerNum,
            'title' => self::TITLE_360,
        ]);
    }

    public function actionCurrentBizhi()
    {
        $startTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d')));
        $endTime = date('Y-m-d H:i:s', time());
        //$loginNum = RegisterDailyStatistics::dailyLoginNumFor360($startTime, $endTime);
        $registerNum = RegisterDailyStatistics::dailyRegisterNum($startTime, $endTime, UserXunruibizhi::className());
        return $this->render('current-view',[
            'loginNum' => null,
            'registerNum' => $registerNum,
            'title' => self::TITLE_BIZHI,
        ]);
    }

    /**
     * Displays a single RegisterDailyStatistics model.
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
     * Deletes an existing RegisterDailyStatistics model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the DailyStatisticsFor360 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegisterDailyStatistics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegisterDailyStatistics::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

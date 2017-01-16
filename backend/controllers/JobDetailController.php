<?php

namespace backend\controllers;

use Yii;
use backend\models\JobDetail;
use backend\models\JobDetailSearch;
use yii\web\NotFoundHttpException;

/**
 * JobDetailController implements the CRUD actions for JobDetail model.
 */
class JobDetailController extends BaseController
{
    /**
     * Lists all JobDetail models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $searchModel = new JobDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single JobDetail model.
     * @param integer $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/


    /**
     * Finds the JobDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JobDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*protected function findModel($id)
    {
        if (($model = JobDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
}

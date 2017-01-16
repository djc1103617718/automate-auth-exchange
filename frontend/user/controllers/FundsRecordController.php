<?php

namespace frontend\user\controllers;

use Yii;
use frontend\models\FundsRecord;
use frontend\models\FundsRecordSearch;
use yii\web\NotFoundHttpException;

/**
 * FundsRecordController implements the CRUD actions for FundsRecord model.
 */
class FundsRecordController extends UserBaseController
{
    /**
     * Lists all FundsRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FundsRecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FundsRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = FundsRecord::STATUS_DELETE;
        if (!$model->update()) {
            Yii::$app->session->setFlash('error', '删除失败');
        } else {
            Yii::$app->session->setFlash('success', '删除成功');
        }
        return $this->redirect(['funds-record/index']);
    }*/

    /**
     * Finds the FundsRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FundsRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FundsRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

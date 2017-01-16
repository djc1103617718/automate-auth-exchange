<?php

namespace backend\controllers;

use Yii;
use backend\models\App;
use backend\models\JobType;
use backend\models\JobTypeSearch;;
use yii\web\NotFoundHttpException;

/**
 * JobTypeController implements the CRUD actions for JobType model.
 */
class JobTypeController extends BaseController
{
    /**
     * Lists all JobType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JobTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JobType model.
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
     * Creates a new JobType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JobType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($ref = Yii::$app->request->get('ref')) {
                return $this->redirect($ref);
            }
            return $this->redirect(['view', 'id' => $model->type_id]);
        } else {
            $idToNameArray = JobType::idToNameArray();
            return $this->render('create', [
                'model' => $model,
                'idToNameArray' => $idToNameArray,
            ]);
        }
    }

    /**
     * Updates an existing JobType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->type_id]);
        } else {
            $idToNameArray = JobType::idToNameArray();
            return $this->render('update', [
                'model' => $model,
                'idToNameArray' => $idToNameArray,
            ]);
        }
    }

    /**
     * Deletes an existing JobType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->virtualDelete()) {
            Yii::$app->session->setFlash('success', '删除成功');
        } else {
            Yii::$app->session->setFlash('error', '删除失败');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the JobType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JobType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JobType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

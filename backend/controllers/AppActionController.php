<?php

namespace backend\controllers;

use Yii;
use backend\models\AppAction;
use backend\models\App;
use backend\models\AppActionSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppActionController implements the CRUD actions for AppAction model.
 */
class AppActionController extends BaseController
{
    /**
     * Lists all AppAction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppAction model.
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
     * Creates a new AppAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($app_id = null)
    {
        $model = new AppAction();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->action_id]);
        } else {
            $app_id = $app_id ? $app_id : $model->app_id;
            if (empty($model->app_name)) {
                $model->app_name = App::findOne($app_id)->app_name;
            }
            $model->app_id = $app_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->app_name = App::findOne($model->app_id)->app_name;
        if (AppAction::isExistJobRelationAppAction($id)) {
            Yii::$app->session->setFlash('error', '不能修改,请确保该动作Action对应的所有任务处于删除或取消或者已完成状态!');
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->update()) {
                return $this->redirect(['view', 'id' => $model->action_id]);
            }
            Yii::$app->session->setFlash('error', '更新失败');
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AppAction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (AppAction::isExistJobRelationAppAction($id)) {
            Yii::$app->session->setFlash('error', '不能删除,请确保该动作Action对应的所有任务处于删除或取消或者已完成状态!');
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);
        if (!$model->deleteAppAction()) {
            Yii::$app->session->setFlash('error', '删除失败!');
        } else {
            Yii::$app->session->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the AppAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppAction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

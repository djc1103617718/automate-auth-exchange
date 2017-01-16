<?php

namespace backend\controllers;

use Yii;
use backend\models\AppActionStep;
use common\helper\JobParam;
use backend\models\JobType;
use backend\models\AppDetailForm;
use backend\models\App;
use backend\models\AppSearch;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * AppController implements the CRUD actions for App model.
 */
class AppController extends BaseController
{
    /**
     * Lists all App models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all App models.
     * @return mixed
     */
    public function actionDetailIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->detailSearch(Yii::$app->request->queryParams);

        return $this->render('detail-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single App model.
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
     * @param $app_id
     * @param null $action_id
     * @param null $step_id
     * @return mixed
     */
    public function actionDetailView($app_id, $action_id = null, $step_id = null)
    {
        $model = App::findOneAppDetail($app_id, $action_id, $step_id);
        $model['app_id'] = $model['aid'];
        $model['action_id'] = $model['cid'];
        return $this->render('detail-view',[
             'model' => $model
        ]);
    }

    /**
     * Creates a new App model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new App();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->app_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing App model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (App::isExistJobRelationApp($id)) {
            Yii::$app->session->setFlash('error', '不能修改,请确保该APP应用下的所有动作Action对应的所有任务处于删除或取消或者已完成状态!');
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->app_id]);
            }
            Yii::$app->session->setFlash('error','修改失败!');
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
     * @param $app_id
     * @param null $action_id
     * @param null $step_id
     * @return string|\yii\web\Response
     */
    /*public function actionDetailUpdate($app_id, $action_id = null, $step_id = null)
    {
        $model = new AppDetailForm();
        $detailFormArray = [];
        foreach (AppDetailForm::findOneAppDetail($app_id, $action_id, $step_id) as $k => $v) {
            if ($k == 'id') {
                $detailFormArray['AppDetailForm']['app_id'] = $v;
            }
            $detailFormArray['AppDetailForm'][$k] = $v;
        }
        $model->load($detailFormArray);
        $job_params = $model->job_param;
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->job_param = $this->getJobParam($model->job_param)){
                Yii::$app->session->setFlash('error', '自动化参数验证失败,请检查输入的数据!');
                $model->job_param = $job_params;
                return $this->render('detail-update',[
                    'model' => $model,
                    'inputArray' => json_decode($model['job_param']) ? json_decode($model['job_param']) : [],
                    'actionStepArray' => JobType::idToStepSymbolArray($app_id),
                    'inputBoxTypeList' => AppActionStep::inputBoxTypeList(),
                    'inputDataTypeList' => AppActionStep::dataTypeList(),
                ]);
            }
            if (!$model->validate() || !$model->update()) {
                Yii::$app->session->setFlash('error', '更新失败,请检查输入的数据!');
                return $this->render('detail-update',[
                    'model' => $model,
                    'inputArray' => json_decode($model['job_param']) ? json_decode($model['job_param']) : [],
                    'actionStepArray' => JobType::idToStepSymbolArray($app_id),
                    'inputBoxTypeList' => AppActionStep::inputBoxTypeList(),
                    'inputDataTypeList' => AppActionStep::dataTypeList(),
                ]);
            }
            return $this->redirect(['detail-view', 'app_id' => $app_id, 'action_id' => $action_id, 'step_id' => $step_id]);
        } else {
            return $this->render('detail-update',[
                'model' => $model,
                'inputArray' => json_decode($model['job_param']) ? json_decode($model['job_param']) : [],
                'actionStepArray' => JobType::idToStepSymbolArray($app_id),
                'inputBoxTypeList' => AppActionStep::inputBoxTypeList(),
                'inputDataTypeList' => AppActionStep::dataTypeList(),
            ]);
        }
    }*/

    /**
     * Deletes an existing App model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (App::isExistJobRelationApp($id)) {
            Yii::$app->session->setFlash('error', '不能删除,请确保该APP应用下的所有动作Action对应的所有任务处于删除或取消或者已完成状态!');
            return $this->redirect(['index']);
        }
        if (!$model->deleteApp()) {
            Yii::$app->session->setFlash('error', '删除失败!');
        } else {
            Yii::$app->session->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the App model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return App the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = App::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 将表单数据组合成jobParam的json格式
     * @param $jobParams
     * @return bool || json
     */
    /*public function getJobParam($jobParams)
    {
        if (JobParam::validateJobParam($jobParams)) {
            return JobParam::getJobParamJson($jobParams);
        }
        return false;
    }*/

}

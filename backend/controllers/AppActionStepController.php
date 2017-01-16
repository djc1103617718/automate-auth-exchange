<?php

namespace backend\controllers;

use common\helper\ArrayAide;
use common\helper\StringAide;
use Yii;
use backend\models\JobType;
use backend\models\AppAction;
use backend\models\AppActionStep;
use backend\models\ActionStepForm;
use backend\models\AppActionStepSearch;
use common\helper\JobParam;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * AppActionStepController implement the CRUD actions for AppActionStep model.
 */
class AppActionStepController extends BaseController
{
    public $jobParamsErrors;
    /**
     * Lists all AppActionStep models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppActionStepSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AppActionStep model.
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCreate($id)
    {
        $model = new ActionStepForm();
        if (!$model->action_id = $id) {
            throw new NotFoundHttpException('缺少 action_id');
        }
        if (!AppActionStep::canCreateStep($id)) {
            Yii::$app->session->setFlash('error', '不能创建,请确保该动作Action对应的所有任务处于删除、取消、已完成的状态!');
        }
        $model->action_name = AppAction::findOne($id)->action_name;
        $app_id = AppAction::findOne($model->action_id)->app_id;
        $actionStepArray = JobType::idToStepSymbolArray($app_id);
        $inputBoxTypeList = JobParam::inputBoxTypeList();
        $inputDataTypeList = JobParam::dataTypeList();
        return $this->render('create', [
            'model' => $model,
            'actionStepArray' => $actionStepArray,
            'inputBoxTypeList' => $inputBoxTypeList,
            'inputDataTypeList' => $inputDataTypeList,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateProcess()
    {
        $model = new ActionStepForm();
        $model->load(Yii::$app->request->post());
        //判断能否创建以及对jobParam参数的dataList进行格式化
        if (AppActionStep::canCreateStep($model->action_id) && $model->jobParamInit()) {
            $model->job_param = $this->formatJobParam($model->job_param);
            if ($model->job_param && $model->save()) {
                return $this->redirect([
                    'view', 'id' => $model->findOneActionStep($model->action_id, $model->step_symbol)->step_id
                ]);
            }
            if (!empty($this->jobParamsErrors[0])) $errMsg =  next($this->jobParamsErrors[0]);
            else $errMsg = empty($model->errors) ? '创建失败!': current($model->errors);
        } else {
            $errMsg = empty($model->errorMsg)? '不能创建,请确保该动作Action对应的所有任务处于删除、取消、已完成的状态!' : ArrayAide::getValue(current($model->errorMsg));
        }

        $model->action_name = AppAction::findOne($model->action_id)->action_name;
        $app_id = AppAction::findOne($model->action_id)->app_id;
        $actionStepArray = JobType::idToStepSymbolArray($app_id);
        $inputBoxTypeList = JobParam::inputBoxTypeList();
        $inputDataTypeList = JobParam::dataTypeList();
        Yii::$app->session->setFlash('error', $errMsg);
        return $this->render('create', [
            'model' => $model,
            'actionStepArray' => $actionStepArray,
            'inputBoxTypeList' => $inputBoxTypeList,
            'inputDataTypeList' => $inputDataTypeList,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = new ActionStepForm();
        $stepModel = $this->findModel($id);
        $model->step_id = $stepModel->step_id;
        $model->action_id = $stepModel->action_id;
        $model->step_symbol = $stepModel->step_symbol;
        $model->job_param = $stepModel->job_param;
        $model->sort = $stepModel->sort;
        $model->status = $stepModel->status;
        $model->isNewRecord = 0;
        $app_id = AppAction::findOne($model->action_id)->app_id;
        $actionStepArray = JobType::idToStepSymbolArray($app_id);
        $inputBoxTypeList = JobParam::inputBoxTypeList();
        $inputDataTypeList = JobParam::dataTypeList();
        $model->action_name = AppAction::findOne($model->action_id)->action_name;
        if (AppActionStep::isExistJobRelationStep($id)) {
            Yii::$app->session->setFlash('error', '不能修改,请确保该自动化步骤所在的动作Action对应的所有任务处于删除、取消、已完成的状态!');
        }
        return $this->render('update', [
            'model' => $model,
            'actionStepArray' => $actionStepArray,
            'inputBoxTypeList' => $inputBoxTypeList,
            'inputDataTypeList' => $inputDataTypeList,
            'inputArray' => json_decode($model['job_param']) ? ActionStepForm::formatJobParamForUpdate($model['job_param']) : [],
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdateProcess($id)
    {
        $model = new ActionStepForm();
        $model->step_id = $id;
        $model->load(Yii::$app->request->post());
        //var_dump($model->job_param);die;
        if (!AppActionStep::isExistJobRelationStep($id) && $model->jobParamInit()) {
            $model->job_param = $this->formatJobParam($model->job_param);
            if ($model->job_param && $model->save()){
                Yii::$app->session->setFlash('success', '修改成功!');
                return $this->redirect(['view', 'id' => $model->findOneActionStep($model->action_id, $model->step_symbol)->step_id]);
            }
            if (!empty($this->jobParamsErrors[0])) {
                $errMsg =  $this->jobParamsErrors[0];
            } else {
                $errMsg = empty($model->errors) ? '修改失败!': current($model->errors);
            }
        } else {
            $errMsg = empty($model->errorMsg)? '不能创建,请确保该动作Action对应的所有任务处于删除、取消、已完成的状态!' : ArrayAide::getValue(current($model->errorMsg));
        }

        $stepModel = $this->findModel($id);
        $model->step_id = $stepModel->step_id;
        $model->action_id = $stepModel->action_id;
        $model->step_symbol = $stepModel->step_symbol;
        $model->job_param = $stepModel->job_param;
        $model->status = $stepModel->status;
        $model->isNewRecord = 0;

        $model->action_name = AppAction::findOne($model->action_id)->action_name;
        $app_id = AppAction::findOne($model->action_id)->app_id;

        $actionStepArray = JobType::idToStepSymbolArray($app_id);
        $inputBoxTypeList = JobParam::inputBoxTypeList();
        $inputDataTypeList = JobParam::dataTypeList();
        Yii::$app->session->setFlash('error', $errMsg);

        return $this->render('update', [
            'model' => $model,
            'actionStepArray' => $actionStepArray,
            'inputBoxTypeList' => $inputBoxTypeList,
            'inputDataTypeList' => $inputDataTypeList,
            'inputArray' => json_decode($model['job_param']) ? ActionStepForm::formatJobParamForUpdate($model['job_param']) : [],
        ]);
    }

    /**
     * Deletes an existing AppActionStep model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (AppActionStep::isExistJobRelationStep($id)) {
            Yii::$app->session->setFlash('error', '不能删除,请确保该自动化步骤所在的动作Action对应的所有任务处于删除、取消、已完成的状态!');
            return $this->redirect(['view', 'id' => $id]);
        }
        $model = $this->findModel($id);
        $model->status = AppActionStep::STATUS_LOCKING;
        if (!$model->update()) {
            Yii::$app->session->setFlash('error', '删除失败!');
        } else {
            Yii::$app->session->setFlash('success', '删除成功!');
        }
        return $this->redirect(['index']);
    }


    /**
     * Finds the AppActionStep model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppActionStep the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppActionStep::findOne(['step_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** 
     * 将表单数据组合成jobParam的json格式
     * @param $jobParams
     * @return string|bool @json|bool
     * @internal param $jobParams
     */
    public function formatJobParam($jobParams)
    {
        $jobParam = new JobParam();
        if ($jobParam->validateJobParam($jobParams)) {
            return JobParam::jobParamJson($jobParams);
        }
        $this->jobParamsErrors = $jobParam->error_msg;
        return false;
    }

}

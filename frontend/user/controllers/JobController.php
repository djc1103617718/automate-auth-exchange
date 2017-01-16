<?php

namespace frontend\user\controllers;

use Yii;
use frontend\models\AppAction;
use frontend\user\models\TaskTemplateForm;
use frontend\models\Job;
use frontend\models\JobSearch;
use yii\web\NotFoundHttpException;

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends UserBaseController
{
    const TASK_TITLE_DRAFT ='草稿箱任务';
    const TASK_TITLE_NEW ='待审核任务';
    const TASK_TITLE_AWAITING ='待执行任务';
    const TASK_TITLE_EXECUTING ='执行中任务';
    const TASK_TITLE_COMPLETE ='已完成任务';
    const TASK_TITLE_ALL ='所有任务';
    const TASK_TITLE_CANCEL = '审核失败任务';
    //const TASK_TITLE_EXPIRED = '失效的任务';

    const ACTION_TYPE_ALL = 'index';
    const ACTION_TYPE_AWAITING = 'awaiting-index';
    const ACTION_TYPE_EXECUTING = 'executing-index';
    const ACTION_TYPE_COMPLETE = 'complete-index';
    const ACTION_TYPE_CANCEL = 'cancel-index';
    const ACTION_TYPE_DRAFT = 'draft-index';
    const ACTION_TYPE_NEW = 'new-index';
    //const ACTION_TYPE_EXPIRED = 'expired-index';


    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionIndex()
    {
        //var_dump(Yii::$app->request->queryParams);die;
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_ALL,
            'action' => self::ACTION_TYPE_ALL,
        ]);
    }

    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionExecutingIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_EXECUTING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_EXECUTING,
            'action' => self::ACTION_TYPE_EXECUTING,
        ]);
    }

    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionCompleteIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_COMPLETE);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_COMPLETE,
            'action' => self::ACTION_TYPE_COMPLETE,
        ]);
    }

    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionAwaitingIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_AWAITING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_AWAITING,
            'action' => self::ACTION_TYPE_AWAITING,
        ]);
    }

    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionNewIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_NEW);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_NEW,
            'action' => self::ACTION_TYPE_NEW,
        ]);
    }

    public function actionCancelIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_CANCEL);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_CANCEL,
            'action' => self::ACTION_TYPE_CANCEL,
        ]);
    }

    /*public function actionExpiredIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $searchModel::JOB_HAS_EXPIRED);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_EXPIRED,
            'action' => self::ACTION_TYPE_EXPIRED,
        ]);
    }*/

    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionDraftIndex()
    {
        $searchModel = new JobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Job::STATUS_DRAFT);

        return $this->render('draft-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_DRAFT,
            'action' => self::ACTION_TYPE_DRAFT,
        ]);
    }

    /**
     * Displays a single Job model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $appAction = AppAction::findOne(['action_name' => $model->job_name]);
        $jobParams = TaskTemplateForm::formatJobParamForUpdate($appAction->action_id, $id);
        return $this->render('view', [
            'model' => $model,
            'jobParams' => $jobParams,
        ]);
    }

    /**
     * Deletes an existing Job model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['draft-index']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDraftDeleteAll()
    {
        if (Job::deleteDraftAll(Yii::$app->user->id)) {
            Yii::$app->session->setFlash('success', '草稿箱已经清空');
        } else {
            Yii::$app->session->setFlash('error', '清空草稿箱发生错误,请联系我们');
        }
        return $this->redirect(['draft-index']);
    }

    /**
     * Finds the Job model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Job the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Job::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

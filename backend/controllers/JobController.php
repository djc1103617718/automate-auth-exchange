<?php

namespace backend\controllers;

use Yii;
use backend\models\Job;
use backend\models\JobSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\components\excel\BaseExcel;

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends BaseController
{
    const TASK_TITLE_DRAFT ='草稿箱任务';
    const TASK_TITLE_NEW ='新任务';
    const TASK_TITLE_AWAITING ='待执行任务';
    const TASK_TITLE_EXECUTING ='执行中任务';
    const TASK_TITLE_COMPLETE ='已完成任务';
    const TASK_TITLE_ALL ='所有任务';
    const TASK_TITLE_CANCEL = '未通过审核任务';
    /**
     * Lists all Job models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();

        $dataProvider = $searchModel->searchDetail($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_ALL,
            'action' => Yii::$app->controller->action->id,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionAwaitingIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();
        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_AWAITING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_AWAITING,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_AWAITING,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionExecutingIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();
        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_EXECUTING);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_EXECUTING,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_EXECUTING,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionCompleteIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();
        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_COMPLETE);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_COMPLETE,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_COMPLETE,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionNewIndex()
    {
        $searchModel = new JobSearch();
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_NEW);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_NEW,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_NEW,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionCancelIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();
        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_CANCEL);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_CANCEL,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_CANCEL,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionDraftIndex()
    {
        $params = Yii::$app->request->queryParams;

        $requestParam = $this->setSearchStr('JobSearch', $params);

        $searchModel = new JobSearch();
        $dataProvider = $searchModel->searchDetail($params, Job::STATUS_DRAFT);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskTitle' => self::TASK_TITLE_DRAFT,
            'action' => Yii::$app->controller->action->id,
            'jobStatus' => Job::STATUS_DRAFT,
            'requestParams' => $requestParam,
        ]);
    }

    public function actionEnsureJob($id)
    {
        $model = $this->findModel($id);
        if (!$model->ensureJob()){
            Yii::$app->session->setFlash('error', '提交审核失败!');
            return $this->redirect(['view', 'id' => $id]);
        }
        Yii::$app->session->setFlash('success', '审核成功!');
        return $this->redirect(['new-index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionCancelJob($id)
    {
        $model = $this->findModel($id);
        if (!$model->cancelJob()){
            Yii::$app->session->setFlash('error', '提交审核失败!');
            return $this->redirect(['view', 'id' => $id]);
        }
        Yii::$app->session->setFlash('success', '审核成功!');
        return $this->redirect(['new-index']);
    }

    /**
     * Excel
     */
    public function actionExport()
    {
        $post = Yii::$app->request->post();

        $jobSearch = new JobSearch();

        if ($requestParam = $this->getSearchParam($post)) {
            $jobSearch->load($requestParam);
        }

        if (isset($post['jobStatus'])) {
            if (isset($post['selection'])) {
                $dataProvider = $jobSearch->searchForExport($post['selection'], $post['jobStatus']);
            } else {
                $dataProvider = $jobSearch->searchForExport(null, $post['jobStatus']);
            }
        } else {
            if (isset($post['selection'])) {
                $dataProvider = $jobSearch->searchForExport($post['selection']);
            } else {
                $dataProvider = $jobSearch->searchForExport(null);
            }
        }
        $data = $dataProvider->getModels();
        $statusList = array_flip(Job::statusArray());
        $jobStatusName = array_search($post['jobStatus'], $statusList);

        $excel = new BaseExcel();
        $excel->fileName = $post['filename']? $post['filename']: '任务列表' . date('y-m-d-h-i-s', time());

        $excel->subject = $jobStatusName? $jobStatusName : '任务列表';
        $excel->run($data);
    }

    /**
     * Displays a single Job model.
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

    /**
     * @param $className
     * @param array $queryParam @Yii::$app->request->queryParams
     * @return string
     */
    public function setSearchStr($className,$queryParam)
    {
        if (isset($queryParam[$className]) && !isset($queryParam[$className][0])) {
            $key = key($queryParam[$className]);
            $val = $queryParam[$className][$key];
            $requestStr = $key.'='.$val;
        } else {
            $requestStr = '';
        }
        return $requestStr;
    }

    /**
     * @param array $queryParams
     * @return array|null
     */
    public function getSearchParam($queryParams)
    {
        if (isset($queryParams['requestParams']) && $str = $queryParams['requestParams']) {
            $requestParam = explode('=', $str);
            $param = ['JobSearch' => [$requestParam[0] => $requestParam[1]]];
        } else {
            $param = null;
        }
        return $param;
    }

    public function actionTest()
    {
        //var_dump($_SERVER);
    }

}

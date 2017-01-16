<?php

namespace backend\controllers;

use Yii;
use backend\models\Job;
use backend\models\App;
use backend\models\AppAction;
use backend\models\TaskTemplateForm;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\components\price\Price as PriceComponent;

error_reporting( E_ALL&~E_NOTICE );
class TaskTemplateController extends BaseController
{
    // 控制器下的操作类型
    const TYPE_CREATE = 1;
    const TYPE_DRAFT_CREATE = 2;
    const TYPE_DRAFT_UPDATE = 3;

    // 微信任务、普通任务、刷榜任务分类下的视图模版名
    const TEMPLATE_NAME_BRUSH_LIST = 'brush-list-job-create';
    const TEMPLATE_NAME_COMMON_TASK = 'job-create';
    const TEMPLATE_NAME_WE_CHAT = 'we-chat-job-create';
    const TEMPLATE_NAME_DRAFT_BRUSH_LIST = 'draft-brush-list-job-create';
    const TEMPLATE_NAME_DRAFT_COMMON_TASK = 'draft-job-create';
    const TEMPLATE_NAME_DRAFT_WE_CHAT = 'draft-we-chat-job-create';
    const TEMPLATE_NAME_BRUSH_LIST_UPDATE = 'draft-brush-list-update';
    const TEMPLATE_NAME_COMMON_TASK_UPDATE = 'draft-update';
    const TEMPLATE_NAME_WE_CHAT_UPDATE = 'draft-we-chat-job-update';

    public function actionApp()
    {
        $appList = TaskTemplateForm::appIdToName();
        return $this->render('app', [
           'appList' => $appList
        ]);
    }

    public function actionAppAjax($app_id)
    {
        if (!$app_id) {
            echo json_encode(['code' => 'false', 'message' => '请选择app!']);
        }
        $actions = AppAction::find()->select(['action_id', 'action_name'])->where(['app_id' => $app_id, 'status' => 1])->asArray()->all();
        if (!$actions) {
            echo json_encode(['code' => 'false', 'message' => 'App平台正在建设中,功能暂未开放!']);
        }
        echo json_encode(['code' => 'true', 'message' => '', 'data' => $actions]);
    }


    /**
     * @param $id
     * @param $type
     * @return string
     * @throws Exception
     */
    public function actionJobCreate($id, $type = null)//action_id
    {
        $appAction = AppAction::findOne(['action_id' => $id, 'status' => AppAction::STATUS_NORMAL]);
        $actions = AppAction::find()->where(['app_id' => $appAction->app_id, 'status' => AppAction::STATUS_NORMAL])->asArray()->all();
        $inputArray = TaskTemplateForm::formatJobParamForView($id);
        if (empty($inputArray)) {
            throw new Exception('自动化步骤参数缺失!');
        }
        $actionPrice = $this->getPublicPriceList($appAction->action_class_name);
        if ($type == 1) {
            $templateFileName = $this->getTemplateFileName($appAction->category, self::TYPE_DRAFT_CREATE);
        } else {
            $templateFileName = $this->getTemplateFileName($appAction->category, self::TYPE_CREATE);
        }

        $data = Yii::$app->request->get();
        if (isset($data['model'])) {
            $model = $data['model'];
        } else {
            $model = null;
        }

        return $this->render($templateFileName,[
            'action_name' => $appAction->action_name,
            'actions' => $actions,
            'inputArray' => $inputArray,
            'action_id' => $id,
            'actionPrice' => $actionPrice,
            'model' => $model,
        ]);
    }

    /**
     * 任务处理
     * @param $action_id
     * @return \yii\web\Response
     */
    public function actionJobProcess($action_id)
    {
        $model = new TaskTemplateForm();
        $model->setScenario('job_create');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['job/new-index']);
        } else {
            $errorMsg = array_values($model->errors);
            Yii::$app->session->setFlash('error', $errorMsg[0]);
            return $this->redirect(['job-create', 'id' => $action_id, 'model' => $model]);
        }
    }

    /**
     * 重设任务
     * @param $action_id
     * @param integer $type @ 为1是草稿任务重置
     * @return \yii\web\Response
     */
    public function actionResetTask($action_id, $type=null)
    {
        return $this->redirect(['job-create', 'id' => $action_id, 'type' => $type]);
    }

    /**
     * 获得模版名称
     * @param $category
     * @param int $type
     * @return string
     * @throws Exception
     */
    private function getTemplateFileName($category, $type)
    {
        if ($type == self::TYPE_DRAFT_CREATE) {
            if ($category == AppAction::CATEGORY_BRUSH_LIST_TASK) {
                $templateFileName = self::TEMPLATE_NAME_DRAFT_BRUSH_LIST;
            } elseif ($category == AppAction::CATEGORY_COMMON_TASK) {
                $templateFileName = self::TEMPLATE_NAME_DRAFT_COMMON_TASK;
            } elseif ($category == AppAction::CATEGORY_WE_CHAT_TASK) {
                $templateFileName = self::TEMPLATE_NAME_DRAFT_WE_CHAT;
            } else {
                throw new Exception('没有找到对应的分类模版');
            }
        } elseif ($type == self::TYPE_CREATE) {
            if ($category == AppAction::CATEGORY_BRUSH_LIST_TASK) {
                $templateFileName = self::TEMPLATE_NAME_BRUSH_LIST;
            } elseif ($category == AppAction::CATEGORY_COMMON_TASK) {
                $templateFileName = self::TEMPLATE_NAME_COMMON_TASK;
            } elseif ($category == AppAction::CATEGORY_WE_CHAT_TASK) {
                $templateFileName = self::TEMPLATE_NAME_WE_CHAT;
            } else {
                throw new Exception('没有找到对应的分类模版');
            }
        } elseif ($type == self::TYPE_DRAFT_UPDATE) {
            if ($category == AppAction::CATEGORY_BRUSH_LIST_TASK) {
                $templateFileName = self::TEMPLATE_NAME_BRUSH_LIST_UPDATE;
            } elseif ($category == AppAction::CATEGORY_COMMON_TASK) {
                $templateFileName = self::TEMPLATE_NAME_COMMON_TASK_UPDATE;
            } elseif ($category == AppAction::CATEGORY_WE_CHAT_TASK) {
                $templateFileName = self::TEMPLATE_NAME_WE_CHAT_UPDATE;
            } else {
                throw new Exception('没有找到对应的分类模版');
            }
        } else {
            throw new Exception('没有找到对应的模版!');
        }
        return $templateFileName;

    }

    /**
     * @param $className
     * @return array
     */
    private function getPublicPriceList($className)
    {
        return PriceComponent::getPublicPriceList($className);
    }

    /**
     * @param $id
     * @return Job
     * @throws NotFoundHttpException
     */
    protected function findOneModel($id)
    {
        $model = Job::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

    public function actionTest()
    {

    }
}

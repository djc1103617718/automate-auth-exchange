<?php

namespace frontend\user\controllers;

use Yii;
use frontend\models\Notice;
use frontend\models\NoticeSearch;
use frontend\user\controllers\UserBaseController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends UserBaseController
{
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
     * Lists all Notice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionNewIndex()
    {
        $searchModel = new NoticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Notice::STATUS_UNREAD);

        return $this->render('new-index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->status = Notice::STATUS_ALREADY_READ;
        $model->save();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /*public function actionRemark($id)
    {
        $model = $this->findModel($id);
        if ($model->status == Notice::STATUS_ALREADY_READ) {
            return $this->redirect(['index']);
        }

        $model->status = Notice::STATUS_ALREADY_READ;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', '成功标记为已读');
        } else{
            Yii::$app->session->setFlash('error', '标记失败');
        }
        return $this->redirect(['index']);
    }*/

    public function actionRemarkRead()
    {
        $post = Yii::$app->request->post();
        $noticeIds = @$post['selection'];
        if (!empty($noticeIds)) {
            Notice::remarkAlready($noticeIds);
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Notice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        $model->status = Notice::STATUS_DELETE;
        if (!$model->update()) {
            Yii::$app->session->setFlash('error', '删除消息失败');
        } else {
            Yii::$app->session->setFlash('success', '删除消息成功');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

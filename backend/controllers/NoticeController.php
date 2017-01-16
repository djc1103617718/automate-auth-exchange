<?php

namespace backend\controllers;

use Yii;
use backend\models\Notice;
use backend\models\NoticeSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoticeController implements the CRUD actions for Notice model.
 */
class NoticeController extends BaseController
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

    /**
     * Displays a single Notice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->status = Notice::STATUS_ALREADY_READ;
        $model->save();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

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
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // = Yii::$app->request->post('id');
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

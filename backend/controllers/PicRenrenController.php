<?php

namespace backend\controllers;

use Yii;
use backend\models\wechatdb\PicRenren;
use backend\models\wechatdb\PicRenrenSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PicRenrenController implements the CRUD actions for PicRenren model.
 */
class PicRenrenController extends BaseController
{
    const TITLE_AWAITING = '未审核图片';
    const TITLE_FAILURE = '审核失败图片';
    const TITLE_SUCCESS = '通过审核图片';
    const TITLE_ALL = '所有图片';

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
     * Lists all PicRenren models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PicRenrenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchUrl' => 'index',
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_ALL
        ]);
    }

    /**
     * @return string
     */
    public function actionAwaitingIndex()
    {
        $searchModel = new PicRenrenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, PicRenren::STATUS_UNCHECKED);

        return $this->render('index', [
            'searchUrl' => 'awaiting-index',
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_AWAITING,
        ]);
    }

    /**
     * @return string
     */
    public function actionSuccessIndex()
    {
        $searchModel = new PicRenrenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, PicRenren::STATUS_CHECKED_SUCCESS);

        return $this->render('index', [
            'searchUrl' => 'success-index',
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_SUCCESS,
        ]);
    }

    /**
     * @return string
     */
    public function actionFailureIndex()
    {
        $searchModel = new PicRenrenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, PicRenren::STATUS_CHECKED_FAILURE);

        return $this->render('index', [
            'searchUrl' => 'failure-index',
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_FAILURE,
        ]);
    }

    /**
     * Displays a single PicRenren model.
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
     * Updates an existing PicRenren model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->picid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionSuccess($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_CHECKED_SUCCESS;
        if (!$model->update()) {
            Yii::$app->session->setFlash('error', '审核失败!');
        } else {
            Yii::$app->session->setFlash('success', '审核成功!');
        }
        return $this->redirect(['awaiting-index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionFailure($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_CHECKED_FAILURE;
        if (!$model->update()) {
            Yii::$app->session->setFlash('error', '审核失败!');
        } else {
            Yii::$app->session->setFlash('success', '审核成功!');
        }
        return $this->redirect(['awaiting-index']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSuccessList()
    {
        $selection = Yii::$app->request->post('selection');
        if (empty($selection)) {
            Yii::$app->session->setFlash('error', '请选择要审核的内容');
            return $this->redirect(Yii::$app->request->referrer);
        }
        if (PicRenren::checkList($selection)) {
            Yii::$app->session->setFlash('success', '审核成功!');
        } else {
            Yii::$app->session->setFlash('error', '审核失败!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionFailureList()
    {
        $selection = Yii::$app->request->post('selection');
        if (empty($selection)) {
            Yii::$app->session->setFlash('error', '请选择要审核的内容');
            return $this->redirect(Yii::$app->request->referrer);
        }
        if (PicRenren::checkList($selection, PicRenren::STATUS_CHECKED_FAILURE)) {
            Yii::$app->session->setFlash('success', '审核成功!');
        } else {
            Yii::$app->session->setFlash('error', '审核失败!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing PicRenren model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', '删除成功!');
        } else {
            Yii::$app->session->setFlash('error', '删除失败!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteList()
    {
        $selection = Yii::$app->request->post('selection');
        if (empty($selection)) {
            Yii::$app->session->setFlash('error', '请选择要审核的内容');
            return $this->redirect(Yii::$app->request->referrer);
        }
        if (PicRenren::deleteAll(['picid' => $selection])) {
            Yii::$app->session->setFlash('success', '删除成功!');
        } else {
            Yii::$app->session->setFlash('error', '删除失败!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PicRenren model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PicRenren the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PicRenren::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\wechatdb\ContentWeibo;
use backend\models\wechatdb\ContentWeiboSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContentWeiboController implements the CRUD actions for ContentWeibo model.
 */
class ContentWeiboController extends BaseController
{
    const TITLE_AWAITING = '未审核内容';
    const TITLE_FAILURE = '审核失败内容';
    const TITLE_SUCCESS = '通过审核内容';
    const TITLE_ALL = '所有内容';

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
     * Lists all ContentWeibo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContentWeiboSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_ALL,
            'searchUrl' => 'index'
        ]);
    }

    /**
     * @return string
     */
    public function actionAwaitingIndex()
    {
        $searchModel = new ContentWeiboSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ContentWeibo::STATUS_NORMAL);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_AWAITING,
            'searchUrl' => 'awaiting-index'
        ]);
    }

    /**
     * @return string
     */
    public function actionFailureIndex()
    {
        $searchModel = new ContentWeiboSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ContentWeibo::STATUS_DELETE);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_FAILURE,
            'searchUrl' => 'failure-index'
        ]);
    }

    /**
     * @return string
     */
    public function actionSuccessIndex()
    {
        $searchModel = new ContentWeiboSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ContentWeibo::STATUS_NICE);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'title' => self::TITLE_SUCCESS,
            'searchUrl' => 'success-index'
        ]);
    }

    /**
     * Displays a single ContentWeibo model.
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
     * Creates a new ContentWeibo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new ContentWeibo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->contentid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing ContentWeibo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->contentid]);
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
        $model->status = $model::STATUS_NICE;
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
        $model->status = $model::STATUS_DELETE;
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
        if (ContentWeibo::checkList($selection)) {
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
        if (ContentWeibo::checkList($selection, ContentWeibo::STATUS_DELETE)) {
            Yii::$app->session->setFlash('success', '审核成功!');
        } else {
            Yii::$app->session->setFlash('error', '审核失败!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing ContentWeibo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', '删除失败!');
        } else {
            Yii::$app->session->setFlash('success', '删除成功!');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ContentWeibo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContentWeibo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContentWeibo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

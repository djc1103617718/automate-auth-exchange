<?php
namespace backend\controllers;

use Yii;
use backend\models\Job;
use backend\models\Notice;
use backend\models\User;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\post\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','signup', 'test', 'type'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $noticeModel = Notice::find()->where(['status' => Notice::STATUS_UNREAD, 'category_name' => Notice::getServerNoticeCategory()])->asArray()->all();
        $jobNum['new'] = Job::find()->where(['status' => Job::STATUS_NEW])->count();
        $jobNum['awaiting'] = Job::find()->where(['status' => Job::STATUS_AWAITING])->count();
        $jobNum['executing'] = Job::find()->where(['status' => Job::STATUS_EXECUTING])->count();
        $jobNum['complete'] = Job::find()->where(['status' => Job::STATUS_COMPLETE])->andWhere(['>', 'finished_time', strtotime(date('Y-m-d'))])->count();
        $userNum['new'] = User::find()->where(['status' => User::STATUS_ACTIVE])->andWhere(['>', 'created_at', strtotime(date('Y-m-d'))])->count();
        $userNum['total'] = User::find()->where(['<>', 'status', User::STATUS_DEFAULT])->count();
        return $this->render('index', [
            'noticeModel' => $noticeModel,
            'jobNum' => $jobNum,
            'userNum' => $userNum,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
            //return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionLogout()
    {
        //var_dump(Yii::$app->session);die;
        Yii::$app->user->logout(false);

        return $this->goHome();
    }

	/*public function actionSignup()
	{
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->goHome();
				}
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}*/

}

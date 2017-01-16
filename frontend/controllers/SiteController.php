<?php
namespace frontend\controllers;

use Yii;
use frontend\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\ForgetPasswordRequestForm;
use frontend\models\ForgetPasswordResetForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    const EVENT_AFTER_SIGN_UP = 'afterSignUp';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'captcha', 'login', 'verification-code-ajax'],
                'rules' => [
                    [
                        'actions' => ['signup', 'captcha', 'login', 'verification-code-ajax'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
                'class' => 'yii\web\ErrorAction',//'frontend\library\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 5,
                'height' => 40,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        Yii::$app->setHomeUrl(['task-template/app']);
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['user/task-template/app']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 通过ajax验证验证码
     */
    public function actionVerificationCodeAjax()
    {
        $sessionCaptcha = Yii::$app->session['__captcha/site/captcha'];
        $verificationCode = Yii::$app->request->get('verificationCode');
        if ($verificationCode && $sessionCaptcha === $verificationCode) {
            echo json_encode(['code'=>1]);
            return false;
        } else {
            echo json_encode(['code' => 0, 'msg' => '验证码错误']);
        }
    }

    /**
     * 通过ajax验证密码
     */
    public function actionAjaxValidatePassword()
    {
        $post = Yii::$app->request->post();
        $user = User::findByUsername($post['account']);
        if (!$user) {
            $user = User::findByEmail($post['account']);
        }
        if (empty($user) || $user->status != 10) {
            echo json_encode(['code' => 0, 'msg' => '用户不存在']);
            return false;
        }
        if ($user->validatePassword($post['password'])) {
            echo json_encode(['code'=>1]);
        } else {
            echo json_encode(['code' => 0, 'msg' => '用户名或者密码错误']);
        }
    }

    public function actionAjaxValidateEmail()
    {
        $get = Yii::$app->request->get();
        $user = User::findOne(['email' => $get['email']]);
        if ($user) {
            echo json_encode(['code' => 0, 'msg' => '邮箱已经存在']);
            return false;
        } else {
            echo json_encode(['code' => 1]);
        }
    }

    public function actionAjaxValidateUsername()
    {
        $get = Yii::$app->request->get();
        $user = User::findOne(['username' => $get['username']]);
        if ($user) {
            echo json_encode(['code' => 0, 'msg' => '用户名已经存在']);
            return false;
        } else {
            echo json_encode(['code' => 1]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout(false);
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', '注册成功,请检查邮箱及时激活账号');
                return $this->redirect(['site/login']);
            }
            Yii::$app->session->setFlash('error', '注册失败');
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @param $username
     * @param $code
     * @return \yii\web\Response
     */
    public function actionActivation($username, $code)
    {
        $user = SignupForm::activeUser($username, $code);
        if ($user) {
            if (Yii::$app->getUser()->login($user)) {
                Yii::$app->session->setFlash('success', '激活成功');
                return $this->goHome();
            }
            return $this->redirect(['site/login']);
        }
        Yii::$app->session->setFlash('error', '激活失败,请重新注册');
        return $this->redirect(['site/signup']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordForget()
    {
        $model = new ForgetPasswordRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '检查你的邮箱以完成密码重置');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '无法通过该邮箱进行密码重置,请检查邮箱地址');
            }
        }

        return $this->render('requestPasswordForgetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return string|\yii\web\Response
     */
    public function actionForgetPasswordReset($token)
    {
        $model = new ForgetPasswordResetForm($token);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->resetPassword()) {
                Yii::$app->session->setFlash('success', '新密码设置成功');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '密码重设失败');
            }
        }
        return $this->render('forgetPasswordReset', [
            'model' => $model,
        ]);
    }

    public function actionTest()
    {

    }

    public function arg()
    {
        return func_get_args();
    }

    public function foo($arg1,$arg2)
    {
        echo $arg1 . $arg2;
    }
}

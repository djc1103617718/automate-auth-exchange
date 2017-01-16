<?php

namespace frontend\user\controllers;

use Yii;
use frontend\user\models\EmailResetRequestForm;
use frontend\user\models\ResetEmailForm;
use frontend\models\User;
use frontend\models\UserDetail;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use frontend\user\models\PasswordResetRequestForm;
use frontend\user\models\ResetPasswordForm;
use frontend\user\models\UserUpdateForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends UserBaseController
{
    /**
     * Displays a single User model.
     * @return mixed
     */
    public function actionView()
    {
        $id = Yii::$app->user->id;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate()
    {
        $id = Yii::$app->user->id;
        if (!$id) {
            throw new ForbiddenHttpException();
        }
        $model = $this->findModel($id);
        $updateModel = new UserUpdateForm();
        $phone = UserDetail::findOne(['user_id' => $id])->phone;
        $updateModel->setAttributes($model->toArray());
        $updateModel->phone = $phone;

        if ($updateModel->load(Yii::$app->request->post()) && $updateModel->update()) {
            return $this->redirect(['view', 'id' => $updateModel->id]);
        } else {
            return $this->render('update', array(
                'model' => $updateModel,
            ));
        }
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', '检查你的邮箱以完成密码重置');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', '无法通过该邮箱进行密码重置,请检查邮箱地址');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param $token
     * @return mixed
     */
    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm($token);
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码设置成功');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRequestEmailReset()
    {
        $model = new EmailResetRequestForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->setEmailResetToken()) {
                Yii::$app->session->setFlash('success', '密码验证成功');
                return $this->redirect(['user/reset-email', 'token' => EmailResetRequestForm::getEmailRestToken()]);
            } else {
                Yii::$app->session->setFlash('error', '密码验证失败');
            }
        }
        return $this->render('requestEmailResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return string|\yii\web\Response
     */
    public function actionResetEmail($token)
    {

        $model = new ResetEmailForm($token);
        if ($model->load(Yii::$app->request->post()) && $model->resetEmail()) {
            Yii::$app->session->setFlash('success', '邮箱更新成功,请检查邮箱,验证是否为有效邮箱地址!');
            return $this->goHome();
        }
        return $this->render('resetEmail', [
            'model' => $model,
        ]);
    }

    public function actionPhoneBind()
    {
        die('功能有待开发');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/7 15:35
 *
 */
namespace backend\controllers;

use Yii;
use app\models\AuthItemMenu;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BaseController extends Controller {

	public function beforeAction($action)
    {

        //拼接完整请求，不可使用requestRoute，不完整
        $module = Yii::$app->controller->module->id;
        $controller = Yii::$app->controller->id;
        $action_id = Yii::$app->controller->action->id;
        $request = '';

        // 预设超级管理员,跳过认证
        if (Yii::$app->user->getId() <= 6 && Yii::$app->user->getId() > 0) {
            return parent::beforeAction($action);
        }

        if (!empty($module) && !in_array($module, ['app-backend'])) {
            $request .= $module;
        }
        if ($controller) {
            if (!empty($request)) {
                $request .= '/';
            }
            $request .= $controller;
        }
        if ($action_id) {
            if (!empty($request)) {
                $request .= '/';
            }
            $request .= $action_id;
        }

        $auth_code = AuthItemMenu::find()->where(['request' => $request])->asArray()->one();

        if ($auth_code) {
            if (Yii::$app->user->can($auth_code['auth_name'])) {
                return parent::beforeAction($action);
            } else {
                if (Yii::$app->request->isAjax) {
                    echo json_encode(['code' => 0, 'msg' => 'no power', 'data' => new \stdClass()]);
                } else {
                    echo 'no power';
                }
            }
        } else {
            if (Yii::$app->request->isAjax) {
                echo json_encode(['code' => 0, 'msg' => 'request not assigned', 'data' => new \stdClass()]);
            } else {
                return $this->redirect(['site/login']);
            }
        }
    }
}
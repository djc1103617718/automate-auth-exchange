<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/7 15:35
 *
 */
namespace frontend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;

class IndexController extends Controller
{
    public $layout = 'walkerskill';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('/index');
    }

}
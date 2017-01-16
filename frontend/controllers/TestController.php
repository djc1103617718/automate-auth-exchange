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

class TestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['f1', 'f2', 'f3','f4'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['f1', 'f2'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['f3','f4'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionF1(){
        echo '11';
    }
    public function actionF2(){
        echo '22';
    }
    public function actionF3(){
        echo '33';
    }
    public function actionF4(){
        echo '44';
    }
}
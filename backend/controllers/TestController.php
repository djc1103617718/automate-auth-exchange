<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/7 15:35
 *
 */
namespace backend\controllers;
use yii\filters\AccessControl;

class TestController extends BaseController
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
                        'actions' => ['f1'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['f2','f3','f4'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionF1(){
        echo '游客页面，未登录的才能看见1';
    }
    public function actionF2(){
        echo '用户页面，登录的才能看见2    增';
    }
    public function actionF3(){
        echo '用户页面，登录的才能看见3    删<br>';
        if (\Yii::$app->user->can('f3')) {
            echo 'F3权限,可删任何记录<br>';
        }
        if (\Yii::$app->user->can('delOwn',['user_id'=>1])) {
            echo 'delOwn权限,只能删自己<br>';
        }
    }
    public function actionF4(){
        echo '用户页面，登录的才能看见4    改';
    }
}
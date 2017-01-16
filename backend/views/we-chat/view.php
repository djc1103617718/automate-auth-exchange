<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\WeChat */

$this->title = $model->wechatid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '微信列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="we-chat-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'wechatid',
            'account',
            'phone',
            [
                'label' => '性别',
                'value' => \yii\helpers\ArrayHelper::getValue(\backend\models\wechatdb\WeChat::genderArray(), $model->gender),
            ],
            'nickname',
            'password',
            'province',
            'headimg',
            'regist_time',
            'city',
            'regist_source',
            'deviceid',
            'extra_field',
        ],
    ]) ?>

</div>

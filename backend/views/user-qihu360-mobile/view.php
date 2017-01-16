<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Qihu360Mobile */

$this->title = '详情:' . $model->wechatid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '360账号列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qihu360-mobile-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'wechatid',
            'phone',
            'regist_time',
            'city',
            'deviceid',
            'status',
            'extra_field',
            'updated_time',
        ],
    ]) ?>

</div>

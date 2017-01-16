<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\UserXunruibizhi */

$this->title = '壁纸详情:' . $model->wechatid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '统一壁纸列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-xunruibizhi-view">
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

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\PicRenren */

$this->title = Yii::t('app', '更新图片:') . $model->picid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '图片列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '图片详情:' . $model->picid, 'url' => ['view', 'id' => $model->picid]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新图片:' . $model->picid);
?>
<div class="pic-renren-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

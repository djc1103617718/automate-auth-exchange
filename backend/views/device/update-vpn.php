<?php

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Device */

$this->title = Yii::t('app', '更新设备VPN:' . $model->nouseid);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设备列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '设备详情:' . $model->nouseid, 'url' => ['view', 'id' => $model->nouseid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

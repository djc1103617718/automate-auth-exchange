<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Vpn */

$this->title = Yii::t('app', '更新VPN:') . $model->vpnid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VPN列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'VPN详情:' . $model->vpnid, 'url' => ['view', 'id' => $model->vpnid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

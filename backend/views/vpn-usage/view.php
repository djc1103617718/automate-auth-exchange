<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\VpnUsage */

$this->title = '记录详情:' . $model->nouseid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VPN使用记录'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-usage-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nouseid',
            'deviceid',
            'ipaddr',
            'access_time',
            'vpnid',
        ],
    ]) ?>

</div>

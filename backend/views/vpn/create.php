<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\Vpn */

$this->title = Yii::t('app', 'Create Vpn');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vpns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

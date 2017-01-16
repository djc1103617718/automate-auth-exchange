<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vip */

$this->title = Yii::t('app', '创建VIP');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIP列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vip-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

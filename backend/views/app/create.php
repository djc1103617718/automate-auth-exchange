<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\App */

$this->title = Yii::t('app', '添加App');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'APP列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

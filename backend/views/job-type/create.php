<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\JobType */

$this->title = Yii::t('app', '创建 Job Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化代号'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-type-create">

    <?= $this->render('_form', [
        'model' => $model,
        'idToNameArray' => $idToNameArray,
    ]) ?>

</div>

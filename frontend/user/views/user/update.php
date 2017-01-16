<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

$this->title = '账户更新';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update" style="clear: both">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

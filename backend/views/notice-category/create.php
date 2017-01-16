<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\NoticeCategory */

$this->title = Yii::t('app', '创建分类');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-category-create">
    <?= $this->render('_form', [
        'model' => $model,
        'categoryList' => $categoryList
    ]) ?>

</div>

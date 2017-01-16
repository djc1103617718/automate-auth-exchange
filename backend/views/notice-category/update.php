<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\NoticeCategory */

$this->title = Yii::t('app', '更新类别');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '所有分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '分类详情:'.$model->category_id, 'url' => ['view', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categoryList' => $categoryList
    ]) ?>

</div>

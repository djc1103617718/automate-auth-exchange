<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\ContentWeibo */

$this->title = Yii::t('app', '更新:') . $model->contentid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '微博内容列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '微博详情:' . $model->contentid, 'url' => ['view', 'id' => $model->contentid]];
$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="content-weibo-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

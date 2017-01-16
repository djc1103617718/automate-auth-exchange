<?php

use yii\widgets\DetailView;
use common\helper\views\ButtonGroup;
use common\helper\views\ColumnDisplay;
use backend\models\wechatdb\PicRenren;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\PicRenren */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '图片列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-renren-view">
    <?php
    $buttons = ButtonGroup::begin();
    $buttons->button('收录', 'btn btn-success', null, 'fa fa-check-square-o')->link(['pic-renren/success', 'id' => $model->picid]);
    $buttons->buttonDefault('废弃', 'btn btn-danger', 'delete')->link(['pic-renren/failure', 'id' => $model->picid]);
    $buttons->buttonDefault('更新', 'btn btn-warning', 'update')->link(['pic-renren/update', 'id' => $model->picid]);
    $buttons::end();
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'picid',
            'user_id',
            'name',
            [
                'attribute' => 'gender',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    if ($model->gender == 'M') {
                        $gender = '男';
                    } elseif ($model->gender == 'F') {
                        $gender = '女';
                    } else {
                        return 'error';
                    }
                    return $gender;
                })
            ],
            [
                'attribute' => 'pic',
                'format' => 'html',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return "<a href='{$model->pic}'><img src='{$model->pic}' width='100px' height='80px'></a>";
                })
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => \yii\helpers\ArrayHelper::getValue($model, function ($model) {
                    return ColumnDisplay::displayStatus($model->status,[
                        PicRenren::STATUS_CHECKED_FAILURE => ['废弃', 'default'],
                        PicRenren::STATUS_UNCHECKED => ['未审核', 'danger'],
                        PicRenren::STATUS_CHECKED_SUCCESS => ['收录', 'success'],
                    ]);
                })
            ],
            'album_mark',
            'date',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\helper\views\ColumnDisplay;
use common\helper\views\ButtonGroup;
use backend\models\wechatdb\ContentWeibo;

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\ContentWeibo */

$this->title = '微博详情:' . $model->contentid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '微博内容列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-weibo-view">
    <?php
        $buttons = ButtonGroup::begin();
        $buttons->button('收录', 'btn btn-success', null, 'fa fa-check-square-o')->link(['content-weibo/success', 'id' => $model->contentid]);
        $buttons->buttonDefault('废弃', 'btn btn-danger', 'delete')->link(['content-weibo/failure', 'id' => $model->contentid]);
        $buttons->buttonDefault('更新', 'btn btn-warning', 'update')->link(['content-weibo/update', 'id' => $model->contentid]);
        $buttons::end();
    ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'contentid',
            'user_id',
            'content',
            'pic',
            'gender',
            'keyword',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => ArrayHelper::getValue($model, function($model){
                    return ColumnDisplay::displayStatus($model->status, [
                        ContentWeibo::STATUS_DELETE => ['废弃', 'default'],
                        ContentWeibo::STATUS_NORMAL => ['未审核', 'danger'],
                        ContentWeibo::STATUS_NICE => ['收录', 'success'],
                    ]);
                })
            ],
            'date',
            'person_mark',
        ],
    ]) ?>

</div>

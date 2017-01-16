<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '所有消息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\frontend\models\NoticeSearch::searchAttributes());
    $searchWidget->setSearchModelName('NoticeSearch');
    $searchWidget->setSearchColor('olive');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['notice/index']));
    $searchWidget::end();
    ?>
    <p>
        <button class="btn btn-default" id="remark-already">标记为已读</button>
    </p>
    <form method="post" action='#' enctype=”multipart/form-data” id="notice-form">
        <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>"/>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'common\components\grid\ActionColumn',
                    'template' => '{view}{delete}',
                ],
                [
                    'class' => \yii\grid\CheckboxColumn::className(),
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return ['value' => $model->notice_id];
                    }
                ],

                ['class' => 'yii\grid\SerialColumn',],

                //'notice_id',
                //'user_id',
                //'category_name',
                'title',
                'description',
                // 'content:ntext',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($status = \frontend\models\Notice::getStatusName($model->status)) {
                            if ($model->status == \frontend\models\Notice::STATUS_ALREADY_READ) {
                                return ColumnDisplay::statusLabel($status, 'label label-default');
                            } elseif ($model->status == \frontend\models\Notice::STATUS_UNREAD) {
                                return ColumnDisplay::statusLabel($status, 'label label-success');
                            }

                        } else {
                            return ColumnDisplay::statusLabel('error', 'label label-danger');
                        }
                    }
                ],
                [
                    'attribute' => 'created_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],
                /*[
                    'attribute' => 'updated_time',
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],*/
            ],
        ]); ?>
    </form>
</div>

<script type="text/javascript">
    $('#remark-already').click(function(){
        var url = '<?= \yii\helpers\Url::to(['notice/remark-read'])?>';
        //console.log(url);
        $('#notice-form').attr('action', url);
        $('#notice-form').submit();
    })
</script>

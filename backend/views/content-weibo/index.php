<?php

use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\wechatdb\ContentWeibo;
use common\helper\views\ButtonGroup;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\ContentWeiboSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-weibo-index">
    <form method="post" id="content-form">
        <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
    <?php
    $buttons = ButtonGroup::begin();
    $buttons->button('未审核列表', 'btn btn-danger', null, 'fa fa-info-circle')->link(['content-weibo/awaiting-index']);
    $buttons->button('通过审核列表', 'btn btn-success', null, 'fa fa-check-circle')->link(['content-weibo/success-index']);
    $buttons->button('审核失败列表', 'btn btn-warning',null,  'fa fa-times-circle')->link(['content-weibo/failure-index']);
    $buttons->button('所有内容', 'btn btn-primary',null , 'fa fa-history')->link(['content-weibo/index']);
    $buttons->button('收录', 'btn btn-info',['id' => 'content-included'] , 'fa fa-check');
    $buttons->button('废弃', 'btn btn-info',['id' => 'content-discarded'] , 'fa fa-close');
    $buttons::end();
    ?>

    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\ContentWeiboSearch::searchAttributes());
    $searchWidget->setSearchModelName('ContentWeiboSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to([$searchUrl]));
    $searchWidget::end();
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{update}{check}{delete}',
                'buttons' => [
                    'check' => function ($url, $model, $key) {
                        return ColumnDisplay::operating('审核', 'fa fa-gavel', ['content-weibo/view', 'id' => $model->contentid]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return ColumnDisplay::operatingDelete(['url'=>['content-weibo/delete', 'id' => $model->contentid], 'content' => '删除以后将从数据库彻底移除,你确定要删除吗?']);
                    }
                ],
            ],
            ['class' => \yii\grid\CheckboxColumn::className()],
            ['class' => 'yii\grid\SerialColumn'],

            //'contentid',
            'user_id',
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<p style='display: inline-block; width: 100px;overflow: auto;'>{$model->content}</p>";
                }
            ],
            //'pic',
            [
                'attribute' => 'gender',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->gender == 'F') {
                        return "<p style='display: inline-block; width: 40px;overflow: auto;'>女</p>";
                    } else {
                        return "<p style='display: inline-block; width: 40px;overflow: auto;'>男</p>";
                    }
                }
            ],
            [
                'attribute' => 'keyword',
                'format' => 'raw',
                'value' => function ($model) {
                    return "<p style='display: inline-block; width: 100px;overflow: auto;'>{$model->keyword}</p>";
                }
            ],
            'person_mark',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return ColumnDisplay::displayStatus($model->status,[
                        ContentWeibo::STATUS_DELETE => ['废弃', 'default'],
                        ContentWeibo::STATUS_NORMAL => ['未审核', 'danger'],
                        ContentWeibo::STATUS_NICE => ['收录', 'success'],
                    ]);
                }
            ],
            'date',
        ],
    ]); ?>
    </form>
</div>
<script type="text/javascript">
    var success_url = "<?= Url::to(['success-list'])?>";
    var failure_url = "<?= Url::to(['failure-list'])?>";
    $(function(){
        $('#content-included').click(function () {
            $('#content-form').attr('action', success_url);
            $('#content-form').submit();
        });
        $('#content-discarded').click(function () {
            $('#content-form').attr('action', failure_url);
            $('#content-form').submit();
        });
    })
</script>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helper\views\ButtonGroup;
use common\helper\views\ColumnDisplay;
use yii\helpers\Url;
use backend\models\wechatdb\PicRenren;
//use common\components\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\PicRenrenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-renren-index">

    <form method="post" id="picture-form">
        <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
        <?php
        $buttons = ButtonGroup::begin();
        $buttons->button('未审核图片', 'btn btn-danger', null, 'fa fa-info-circle')->link(['pic-renren/awaiting-index']);
        $buttons->button('通过审核图片', 'btn btn-success', null, 'fa fa-check-circle')->link(['pic-renren/success-index']);
        $buttons->button('审核失败图片', 'btn btn-warning',null,  'fa fa-times-circle')->link(['pic-renren/failure-index']);
        $buttons->button('所有图片', 'btn btn-primary',null , 'fa fa-history')->link(['pic-renren/index']);
        $buttons->button('收录', 'btn btn-info',['id' => 'picture-included'] , 'fa fa-check');
        $buttons->button('废弃', 'btn btn-info',['id' => 'picture-discarded'] , 'fa fa-close');
        $buttons->button('删除', 'btn btn-info',['id' => 'picture-delete'] , 'fa fa-trash');
        $buttons::end();
        ?>

        <?php
        $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
        $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\PicRenrenSearch::searchAttributes());
        $searchWidget->setSearchModelName('PicRenrenSearch');
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
                        return ColumnDisplay::operating('审核', 'fa fa-gavel', ['pic-renren/view', 'id' => $model->picid]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return ColumnDisplay::operatingDelete(['url'=>['pic-renren/delete', 'id' => $model->picid], 'content' => '删除以后将从数据库彻底移除,你确定要删除吗?']);
                    }
                ],
            ],
            ['class' => \yii\grid\CheckboxColumn::className()],
            ['class' => 'yii\grid\SerialColumn'],

            //'picid',
            'user_id',
            'name',
            [
                'attribute' => 'gender',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->gender == 'M') {
                        return '&nbsp;&nbsp;&nbsp; 男 &nbsp;&nbsp;&nbsp;';
                    } else {
                        return '&nbsp;&nbsp;&nbsp; 女 &nbsp;&nbsp;&nbsp;';
                    }
                }
            ],
            [
                'attribute' => 'pic',
                'format' => 'html',
                'value' => function ($model) {
                    return "<a href='{$model->pic}'><img src='{$model->pic}' width='100px' height='80px'></a>";
                }
            ],
            'album_mark',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return ColumnDisplay::displayStatus($model->status,[
                        PicRenren::STATUS_CHECKED_FAILURE => ['废弃', 'default'],
                        PicRenren::STATUS_UNCHECKED => ['未审核', 'danger'],
                        PicRenren::STATUS_CHECKED_SUCCESS => ['收录', 'success'],
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
    var delete_url = "<?= Url::to(['delete-list'])?>";

    $(function(){
        $('#picture-included').click(function () {
            $('#picture-form').attr('action', success_url);
            $('#picture-form').submit();
        });

        $('#picture-discarded').click(function () {
            $('#picture-form').attr('action', failure_url);
            $('#picture-form').submit();
        });

        $('#picture-delete').click(function () {
            $('#picture-form').attr('action', delete_url);
            deleteList();
        });
    });

    function deleteList(title='删除提醒!',content='删除以后将无法找回,你确定要删除吗?',method='post') {

        var d = dialog({
            title: title,
            content: content,
            okValue: '是',
            ok: function () {//回调函数
                $('#picture-form').submit();
            },
            cancelValue: '否',
            cancel: function () {//回调函数

            }
        });

        d.show();
    }
</script>
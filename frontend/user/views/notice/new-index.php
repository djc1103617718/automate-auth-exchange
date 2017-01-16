<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新消息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\frontend\models\NoticeSearch::searchAttributes());
    $searchWidget->setSearchModelName('NoticeSearch');
    $searchWidget->setSearchColor('olive');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['notice/new-index']));
    $searchWidget::end();
    ?>
    <p>
        <button class="btn btn-default" id="remark-already">标记为已读</button>
    </p>
    <form action="#" method="post" id="notice-form">
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
                ['class' => 'yii\grid\SerialColumn'],

                //'notice_id',
                //'category_name',
                'title',
                'description',
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

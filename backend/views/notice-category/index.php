<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;
use backend\models\NoticeCategory;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NoticeCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '所有分类');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-category-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\NoticeCategorySearch::searchAttributes());
    $searchWidget->setSearchModelName('NoticeCategorySearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['notice-category/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            'category_id',
            'category_name',
            'pid',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $array = [
                        NoticeCategory::STATUS_NORMAL => ['正常', 'success'],
                        NoticeCategory::STATUS_DELETE => ['删除', 'default']
                    ];
                    return ColumnDisplay::displayStatus($model->status, $array);
                }
            ],
            [
                'attribute' => 'created_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_time',
                'format' => ['date','php:Y-m-d H:i:s'],
            ],


        ],
    ]); ?>
</div>

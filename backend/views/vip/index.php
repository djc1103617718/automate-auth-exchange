<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use \common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'VIP列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vip-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\VipSearch::searchAttributes());
    $searchWidget->setSearchModelName('VipSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['vip/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'common\components\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],

            'vip_id',
            'vip_name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == \backend\models\Vip::STATUS_DELETE) {
                        return ColumnDisplay::statusLabel('删除', 'label label-default');
                    }
                    return ColumnDisplay::statusLabel('正常', 'label label-success');
                }

            ],
            [
                'attribute' => 'created_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_time',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]); ?>
</div>

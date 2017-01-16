<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\UserXueruibizhiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '统一壁纸列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-xunruibizhi-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\UserXunruibizhiSearch::searchAttributes());
    $searchWidget->setSearchModelName('UserXunruibizhiSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{view}'
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'wechatid',
            'phone',
            'regist_time',
            'city',
            'deviceid',
            // 'status',
            // 'extra_field',
            'updated_time',
        ],
    ]); ?>
</div>

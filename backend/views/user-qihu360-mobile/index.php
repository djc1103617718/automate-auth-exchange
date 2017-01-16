<?php

use common\components\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\Qihu360MobileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '360账号列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qihu360-mobile-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\UserQihu360MobileSearch::searchAttributes());
    $searchWidget->setSearchModelName('UserQihu360MobileSearch');
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
            'city',
            'deviceid',
            'status',
            'regist_time',

            //'extra_field',
            //'updated_time',
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use common\components\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\WeChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '微信账号列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="we-chat-index" style="overflow-x: auto;">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\WeChatSearch::searchAttributes());
    $searchWidget->setSearchModelName('WeChatSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['we-chat/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\components\grid\ActionColumn',
                'template' => '{view}'
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'wechatid',
            'account',
            'phone',
            [
                'attribute' => 'gender',
                'value' => function ($model) {
                    return \yii\helpers\ArrayHelper::getValue(\backend\models\wechatdb\WeChat::genderArray(), $model->gender);
                }
            ],
            'job_num',
            'commission',
            'nickname',
            'password',
            //'province',
            [
                'attribute' => 'headimg',
                'format' => 'html',
                'value' => function ($model) {
                    return "<img src=$model->headimg width='80px' height='100px' />";
                }
            ],
            //'regist_time',
            [
                'label' => '城市',
                'attribute' => 'cityName.cityname',
            ],
            //'updated_time',
            'regist_source',
            //'deviceid',
            //'extra_field',
        ],
    ]); ?>
</div>
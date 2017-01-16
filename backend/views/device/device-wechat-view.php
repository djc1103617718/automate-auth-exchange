<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\WeChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '设备ID:'.$device->deviceid);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="we-chat-index" style="overflow-x: auto;">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\WeChatSearch::searchAttributes());
    $searchWidget->setSearchModelName('WeChatSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['device/device-wechat-view', 'id' => $device->nouseid]));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\components\grid\ActionColumn',
                'template' => '{online_time}',
                'buttons' => [
                    'online_time' => function ($url, $model, $key) {
                    return \common\helper\views\ColumnDisplay::operating('在线时长', 'fa fa-eye', ['wechat-online-time/account-index', 'account' => ($model->account)? $model->account:$model->phone]);
                    }
                ]
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //'wechatid',
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
            'regist_time',
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
<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use \common\helper\views\ColumnDisplay;
use yii\helpers\Url;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\wechatdb\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">
    <?php
    $button = ButtonGroup::begin();
    $button->button('IOS设备列表', 'btn btn-success')->link('device/ios-device-index');
    $button->button('Android设备列表', 'btn btn-primary')->link('device/android-device-index');
    $button->button('所有设备列表', 'btn btn-info')->link('device/device-index');
    ButtonGroup::end();
    ?>

    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\wechatdb\DeviceSearch::searchAttributes2());
    $searchWidget->setSearchModelName('DeviceSearch');
    $searchWidget->setSearchColor('gray');
    $searchWidget->setSearchAttribute($searchAttribute);
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to([$url]));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => '\common\components\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return ColumnDisplay::operating('修改VPN', 'fa fa-pencil-square', ['device/update-vpn', 'id' => $model->nouseid]);
                    }
                ]
            ],
            ['class' => 'yii\grid\SerialColumn'],
            //'nouseid',
            [
                'label' => '设备ID',
                'attribute' => 'deviceid',
            ],

            [
                'label' => '设备状态',
                'format' => 'raw',
                'value' => function($model){
                    $runTime = strtotime($model['last_connect_time'])+600;
                    if (time() <= $runTime) {
                        return ColumnDisplay::statusLabel('运行中', 'label label-success');
                    } else {
                        return ColumnDisplay::statusLabel('休眠中', 'label label-warning');
                    }
                }
            ],
            [
                'label' => '最后运行时间',
                'attribute' => 'last_connect_time',
            ],
            [
                'label' => 'VPN名称',
                'value' => function ($model) {
                    $vpn = \backend\models\wechatdb\Vpn::findOne($model->vpnid);
                    if ($vpn['vpnname']) {
                        return $vpn->vpnname . '(' . ($model->vpnid) . ')';
                    } else {
                        return '未命名' . '(' . $model->vpnid . ')';
                    }
                }
            ],
            [
                'attribute' => 'city',
                'format' => 'raw',
                'value' => function ($model) {
                    $cityList = array_flip(\backend\models\wechatdb\City::cityList());
                    return array_search($model->city, $cityList);
                }
            ],
            'localip',

            [
                'label' => '运行状态',
                'value' => function ($model) {
                    $job_type =\backend\models\JobType::find()
                        ->select('job_type_name')
                        ->where(['status' => \backend\models\JobType::STATUS_NORMAL, 'step_symbol' => $model['last_job_type']])
                        ->one();
                    if ($job_type) {
                        return $job_type->job_type_name;
                    } else {
                        return '未设置job_type的关联';
                    }
                }
            ],
            //'last_job_param',
            //'account',
        ],
    ]); ?>
</div>

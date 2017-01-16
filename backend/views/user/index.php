<?php

use yii\helpers\Html;
use common\components\grid\GridView;
use common\helper\views\ColumnDisplay;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '用户列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php
    $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
    $searchWidget->setDropdownlistAttributes(\backend\models\UserSearch::searchAttributes());
    $searchWidget->setSearchModelName('UserSearch');
    //$searchWidget->setSearchColor('default');
    $searchWidget->setSearchBoxLength(4);
    $searchWidget->setRequestUrl(\yii\helpers\Url::to(['user/index']));
    $searchWidget::end();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'common\components\grid\ActionColumn',
                'template' => '{view}{delete}',
                'buttons' => [

                    'delete' => function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to(['user/lock', 'id' => $model->id]);
                        if ($model->status == 10) {
                            return Html::a('&nbsp;&nbsp;<span class="fa fa-lock">冻结</span>', $url, ['title' => '冻结']);
                        } else {
                            return Html::a('&nbsp;&nbsp;<span class="fa fa-key">解冻</span>', $url, ['title' => '冻结']);
                        }

                    },
                ],
                'headerOptions' => ['width' => '80'],
            ],
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            'vip_name',
            'shop_name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == \backend\models\User::STATUS_DELETED) {
                        return ColumnDisplay::statusLabel('已冻结', 'label label-default');
                    } elseif ($model->status == \backend\models\User::STATUS_DEFAULT) {
                        return ColumnDisplay::statusLabel('未激活', 'label label-warning');
                    } elseif ($model->status == \backend\models\User::STATUS_ACTIVE) {
                        return ColumnDisplay::statusLabel('正常', 'label label-success');
                    } else {
                        return ColumnDisplay::statusLabel('error', 'label label-danger');
                    }
                }
            ],
            [
                'label' => '手机号',
                'value' => 'userDetail.phone'
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]); ?>
</div>

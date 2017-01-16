<?php

use yii\grid\GridView;
use common\helper\views\ColumnDisplay;
use frontend\models\FundsRecord;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FundsRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '资金记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funds-record-index">

    <div>
        <?php
            $searchWidget =  \common\components\searchWidget\SearchWidget::begin();
            $searchWidget->setDropdownlistAttributes(\frontend\models\FundsRecordSearch::searchAttributes());
            $searchWidget->setSearchModelName('FundsRecordSearch');
            $searchWidget->setSearchColor('olive');
            $searchWidget->setSearchBoxLength(4);
            $searchWidget->setRequestUrl(\yii\helpers\Url::to(['funds-record/index']));
            $searchWidget::end();
        ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return ColumnDisplay::operating('查看', 'fa fa-eye', $url);
                    },
                ],

            ],
            ['class' => 'yii\grid\SerialColumn'],

            'funds_record_id',
            'record_name',
            [
                'attribute' => 'funds_num',
                'value' => function($model){
                    if ($model->type == FundsRecord::TYPE_EXPENSES) {
                        return $model->funds_num/100;
                    } elseif ($model->type == FundsRecord::TYPE_RECHARGE) {
                        return $model->funds_num/100;
                    } elseif($model->type == FundsRecord::TYPE_REFUND){
                        return $model->funds_num/100;
                    } else {
                        return 'error';
                    }
                }
            ],
            [
                'attribute' => 'current_balance',
                'value' => function($model){
                    return $model->current_balance/100;
                },
                //'filter'=>Html::activeTextInput($searchModel, 'current_balance',['class'=>'form-control'])
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model) {
                    $array = [
                        FundsRecord::TYPE_EXPENSES => ['消费', 'primary'],
                        FundsRecord::TYPE_REFUND => ['退款', 'warning'],
                        FundsRecord::TYPE_RECHARGE => ['充值', 'success'],
                    ];
                    return ColumnDisplay::displayStatus($model->type,$array);
                }
            ],
            //'status',
            ColumnDisplay::dateValue('created_time'),
            //ColumnDisplay::dateValue('updated_time'),
        ],
    ]); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '#operating-delete', function(){

    });

</script>

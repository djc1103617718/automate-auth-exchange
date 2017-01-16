<?php

/* @var $this yii\web\View */
/* @var $model backend\models\wechatdb\RegisterDailyStatistics */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '每日账号统计列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-statistics-for360-view">
    <table id="w0" class="table table-striped table-bordered detail-view">
        <tr><th>注册量</th><td><?= $registerNum?></td></tr>
        <tr><th>登录量</th><td><?= $loginNum?></td></tr>
        <tr><th>统计日期</th><td><?= date('Y-m-d', time())?></td></tr>
        <tr><th>统计截止时间</th><td><?= date('Y-m-d H:i:s', time())?>(当下)</td></tr>
    </table>
</div>

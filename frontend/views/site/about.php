<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>


    <code><?= __FILE__ ?></code>
</div>
<?php echo DatePicker::widget([
    'name' => 'Article[created_at]',
    'options' => ['placeholder' => '...'],
    //value值更新的时候需要加上
    'value' => '2016-05-03',
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true,
    ]
]);

echo '<label>时间</label>';
echo DateTimePicker::widget([
    'name' => 'Article[created_at]',
    'options' => ['placeholder' => ''],
    //注意，该方法更新的时候你需要指定value值
    'value' => '2016-05-03 22:10:10',
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'yyyy-mm-dd HH:ii:ss',
        'todayHighlight' => true
    ]
]);
?>



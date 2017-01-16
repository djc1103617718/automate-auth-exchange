<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <?php
            foreach( $keys as $key ) {
                echo '<th class="col-sm-'.$width.'">'.$key.'</th>';
            }
            if( !empty($actions) ) {
                echo '<th class="col-sm-'.$width.'">操作</th>';
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            echo $content;
        ?>
    </tbody>

</table>

<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pageInfo
]);
?>
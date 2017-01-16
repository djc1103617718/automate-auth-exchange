<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$request = empty( $model['request'] ) ? 'javascript:;' : '?r='.$model['request'];
?>

<li class="side-menu-<?= $model['id'] ?>"><a href="<?= $request ?>" data-id="<?= $model['id'] ?>"><?= $model['name'] ?></a>
    <?php if( $model['type'] == 1 ) {
        echo '<ul class="submenu">'.$content.'</ul>';
    }?>
</li>
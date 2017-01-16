<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<?php
        if( $model['type'] == 1 ) {
            $open = ( isset($_COOKIE['current_menu_id']) && $_COOKIE['current_menu_id'] == $model['id'] ) ? 'open' : '';
            echo '<li class="dropdown '.$open.'">
                    <a href="javascript:;" data-id="'.$model['id'].'" class="dropdown-toggle nav_more_menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$model['name'].' <span class="caret"></span></a>';
            echo '</li>';
        }
        else{
            $request = empty( $model['request'] ) ? 'javascript:;' : '?r='.$model['request'];
            echo '<li><a href="'.$request.'" data-type="0" data-id="'.$model['id'].'" data-parent="'.$model['parent'].'">'.$model['name'].'</a></li>';
        }
?>
<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
    <tr>
        <?php
            foreach( $model as $key => $val ) {
                echo Html::tag('td', Html::encode($val), ['class' => 'col-sm-'.$width]);
            }
            if( !empty($actions) ) {
                $str = '';
                foreach( $actions as $action ) {
                    $request_arr = [ $action['url'] ];
                    $flag = 1;
                    if( !empty( $action['params'] ) ) {
                        foreach( $action['params'] as $param ) {
                            if( !empty($model[$param]) ) {
                                $request_arr[ $param ] = $model[ $param ];
                            }
                            else{
                                $flag = 0;
                                break;
                            }
                        }
                    }
                    if( $flag == 1 ) {
                        if( !empty($action['alert']) ) {
                            $str .= Html::a( $action['name'], $request_arr, ['class' => 'profile-link', 'onclick' => "return confirm('".$action['alert']."')"]);
                        }
                        else {
                            $str .= Html::a( $action['name'], $request_arr, ['class' => 'profile-link']);
                        }
                    }
                    else $str .= '<a href="javascript:alert(\'参数缺失\');">'.$action['name'].'</a>';
                    $str .= '&nbsp&nbsp&nbsp&nbsp';
                }
                echo Html::tag('td', $str, ['class' => 'col-sm-'.$width]);
            }
        ?>
    </tr>
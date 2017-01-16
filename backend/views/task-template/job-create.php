<?php

use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use common\helper\JobParam;

$this->title = '';
?>
<div style="position:relative; left:50%;width:100%;">
    <div style="position:relative; left:-50%;width:100%;text-align: center;">
        <p><h1 style="margin-top: 20px; margin-bottom: 50px;color: #007bb6"><?=$action_name?></h1></p>
        <div style="float:left;position:relative;width:30%;margin-left: 30px; ">
            <p><a class="btn btn-primary" style="width:80%;" href="<?= Url::to(['task-template/app'])?>">返回</a></p>
            <?php
            if ($actions) {
                foreach ($actions as $item) {
                    ?>
                    <p><a class="btn btn-primary" style="width:80%;" href="<?= Url::to(['task-template/job-create', 'id' => $item['action_id']])?>"><?= $item['action_name'] ?></a></p>
                    <?php
                }
            } ?>
        </div>
        <div style="float:left;position:relative;width:60%;margin-left: 10px; padding-left:20px; border-left: dashed #d2d6de 1px;" >
            <form class="form-horizontal" role="form" id='job_create_form' action=" <?= Url::to(['job-process', 'action_id' => $action_id])?> " method='post'>
                <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
                <input type="hidden" name="TaskTemplateForm[action_id]" value="<?=$action_id ?>">
                <div 'id'='inputContainer' style = "text-align: left">
                <?php
                if ($inputArray) {
                    foreach ($inputArray as $step_id => $step) {
                        foreach ($step as $arg => $item) {
                            if ($item['input_box_type'] == JobParam::INPUT_BOX_TYPE_INPUT) {
                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item['input_box_name']}</label>";
                                echo "<div class='col-sm-8'>";
                                if (isset($model['job_params'][$step_id][$arg])) {
                                    echo "<input name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control' value=" . "{$model['job_params'][$step_id][$arg]}" . ">";
                                } else {
                                    echo "<input name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control'>";
                                }
                                echo "</div>";
                                echo "</div>";
                            } elseif ($item['input_box_type'] == JobParam::INPUT_BOX_TYPE_SELECT) {
                                if (!$item['data_list']) {
                                    throw new ErrorException('select 输入框缺少必要的枚举值');
                                }

                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item['input_box_name']}</label>";
                                echo "<div class='col-sm-8'>";
                                echo "<select class='form-control' name='TaskTemplateForm[job_params][$step_id][$arg]'>";
                                foreach ($item['data_list'] as $label => $value) {
                                    if ($model['job_params'][$step_id][$arg] && $model['job_params'][$step_id][$arg] == $value) {
                                        echo "<option value='{$value}' selected='selected'>$label</option>";
                                    } else {
                                        echo "<option value='{$value}'>$label</option>";
                                    }
                                }
                                echo "</select></div>";
                                echo "</div>";
                            } elseif ($item['input_box_type'] == JobParam::INPUT_BOX_TYPE_TEXTAREA) {
                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item['input_box_name']}</label>";
                                echo "<div class='col-sm-8'>";
                                if (isset($model['job_params'][$step_id][$arg])) {
                                    echo "<textarea name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control' rows='3'>{$model['job_params'][$step_id][$arg]}</textarea>";
                                } else {
                                    echo "<textarea name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control' rows='3'></textarea>";
                                }

                                echo "</div>";
                                echo "</div>";
                            } elseif ($item['input_box_type'] == JobParam::INPUT_BOX_TYPE_RADIO) {
                                if (!$item['data_list']) {
                                    throw new ErrorException('select 输入框缺少必要的枚举值');
                                }

                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item['input_box_name']}</label>";
                                echo "<div class='col-sm-8'>";
                                foreach ($item['data_list'] as $label => $value) {
                                    if (isset($model['job_params'][$step_id][$arg]) && $model['job_params'][$step_id][$arg] == $value) {
                                        echo "<input type='radio' name='TaskTemplateForm[job_params][$step_id][$arg]' value= '$value' checked='checked'/>$label</input>";
                                    } else {
                                        echo "<input type='radio' name='TaskTemplateForm[job_params][$step_id][$arg]' value= '$value' />$label</input>";
                                    }

                                }
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                    }
                }
            ?>

        </div>
        <div>
            <?php if ($actionPrice) {
                foreach ($actionPrice as $key => $item) {
                    echo "<div class='form-group'>";
                    echo "<label for='inputEmail3' class='col-sm-2 control-label'>" . $item['label'] . "</label>";
                    echo "<div class='col-sm-8'>";
                    echo "<select class='form-control' name='TaskTemplateForm[$key]'>";
                    foreach ($item['values'] as $k => $v) {
                        if (isset($model[$key]) && $model[$key] == $v) {
                            echo "<option value='$v' selected='selected'>$v</option>";
                        } else {
                            echo "<option value='$v'>$v</option>";
                        }
                    }
                    echo '</select></div>';
                    echo '</div>';
                }
            }?>
            <div class='form-group'>
                <label for='inputEmail3' class='col-sm-2 control-label'>任务量</label>
                <div class='col-sm-8'>
                    <input  name='TaskTemplateForm[num]' class='form-control' id='inputEmail3' placeholder='' value='<?=isset($model['num'])?$model["num"]:"";?>'>
                </div>
            </div>
            <div class='form-group'>
                <label for='inputEmail3' class='col-sm-2 control-label'>用户名</label>
                <div class='col-sm-8'>
                    <input  name='TaskTemplateForm[username]' class='form-control' id='inputEmail3' placeholder='' value='<?=isset($model['username'])?$model["username"]:"";?>'>
                </div>
            </div>
            <div class='form-group'>
                <label for='inputEmail3'  class='col-sm-2 control-label'>过期时间</label>
                <div class='col-sm-8'>
                    <?= DateTimePicker::widget([
                        'name' => 'TaskTemplateForm[expire_time]',
                        'options' => ['placeholder' => '请输入任务截止时间'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => isset($model["expire_time"])?$model["expire_time"]:null,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd HH:ii:ss',
                            'todayHighlight' => true
                        ]
                    ]);
                    ?>

                </div>
            </div>
            <div class='form-group'>
                <label for='inputEmail3' name='TaskTemplateForm[job_remark]' class='col-sm-2 control-label'>任务备注</label>
                <div class='col-sm-8'>
                    <input  name='TaskTemplateForm[job_remark]' class='form-control' id='inputEmail3' placeholder='' value='<?=isset($model["job_remark"])?$model["job_remark"]:"";?>'>
                </div>
            </div>
            <div class="btn-group" style="margin-bottom: 20px;">

                <button type='submit' class='btn btn-primary' style="display:inline-block;padding:3px 12px;">提交</button>
                <!--<button type="button" id = 'draft-button' class="btn btn-primary" style="display:inline-block;padding:3px 12px;">草稿</button>-->
                <button type="reset" id = "reset-form" class="btn btn-primary" id = "reset-form" style="display:inline-block;padding:3px 12px;">重置</button>

            </div>

        </div>

        </form>
    </div>
</div>
</div>
<script type="text/javascript">
    $('#draft-button').click(function(){
        var url = "<?= Url::to(['draft-job-process', 'action_id' => $action_id]);?>";
        $('#job_create_form').attr('action', url);
        $('#job_create_form').submit();
    });
    $('#reset-form').click(function(){
        var url = "<?= Url::to(['reset-task', 'action_id' => $action_id]);?>";
        $('#job_create_form').attr('action', url);
        $('#job_create_form').submit();
    })
</script>
<?php

use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use common\helper\JobParam;

$this->title = '草稿箱';
?>
<div style="position:relative; left:50%;width:100%;">
    <div style="position:relative; left:-50%;width:100%;text-align: center">
        <p><h1 style="margin-top: 20px; margin-bottom: 50px;color:#007bb6"><?=$action_name?></h1></p>
        <div style="width:80%;margin: 0 auto; text-align: center" >
            <form class="form-horizontal" role="form" id='job_create_form' action=" <?= Url::to(['task-template/draft-update', 'id' => $model['job_id']])?> " method='post'>
                <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
                <input type="hidden" name="TaskTemplateForm[job_id]" value="<?=$model['job_id'] ?>">
                <div 'id'='inputContainer' style = "text-align: left">
                <?php
                if ($inputArray) {
                    foreach ($inputArray as $step_id => $args) {
                        foreach ($args as $arg => $item) {
                            if ($item[JobParam::INPUT_BOX_TYPE] == JobParam::INPUT_BOX_TYPE_INPUT) {
                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item[JobParam::INPUT_BOX_NAME]}</label>";
                                echo "<div class='col-sm-8'>";
                                echo "<input name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control' value='{$item["value"]}'>";
                                echo "</div>";
                                echo "</div>";
                            } elseif ($item[JobParam::INPUT_BOX_TYPE] == JobParam::INPUT_BOX_TYPE_SELECT) {
                                if (!$item[JobParam::DATA_LIST]) {
                                    throw new ErrorException('select 输入框缺少必要的枚举值');
                                }

                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item[JobParam::INPUT_BOX_NAME]}</label>";
                                echo "<div class='col-sm-8'>";
                                echo "<select class='form-control' name='TaskTemplateForm[job_params][$step_id][$arg]'>";
                                foreach ($item[JobParam::DATA_LIST] as $k => $v) {
                                    if ($v == $item['value']) {
                                        echo"<option value='{$v}' selected='selected'>$k</option>";
                                    } else {
                                        echo"<option value='{$v}'>$k</option>";
                                    }
                                }
                                echo"</select></div>";
                                echo "</div>";
                            } elseif ($item[JobParam::INPUT_BOX_TYPE] == JobParam::INPUT_BOX_TYPE_TEXTAREA) {
                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item[JobParam::INPUT_BOX_NAME]}</label>";
                                echo "<div class='col-sm-8'>";
                                echo "<textarea name='TaskTemplateForm[job_params][$step_id][$arg]' class='form-control' rows='3'>{$item['value']}</textarea>";
                                echo "</div>";
                                echo "</div>";
                            } elseif ($item[JobParam::INPUT_BOX_TYPE] == JobParam::INPUT_BOX_TYPE_RADIO) {
                                if (!$item[JobParam::DATA_LIST]) {
                                    throw new ErrorException('select 输入框缺少必要的枚举值');
                                }

                                echo "<div class='form-group'>";
                                echo "<label for='inputEmail3' class='col-sm-2 control-label'>{$item[JobParam::INPUT_BOX_NAME]}</label>";
                                echo "<div class='col-sm-8'>";
                                foreach ($item[JobParam::DATA_LIST] as $k => $v) {
                                    if ($item['value'] == $v) {
                                        echo "<input type='radio' name='TaskTemplateForm[job_params][$step_id][$arg]' value= '$v' checked='checked'/>$k</input>";
                                    } else {
                                        echo "<input type='radio' name='TaskTemplateForm[job_params][$step_id][$arg]' value= '$v' />$k</input>";
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
                    echo "<option value=''>请选择...</option>";
                    foreach ($item['values'] as $k => $v) {
                        if ($v == $model->$key) {
                            echo "<option value='$v' selected='selected' >$v</option>";
                        }
                        echo "<option value='$v'>$v</option>";
                    }
                    echo '</select></div>';
                    echo '</div>';
                }
            }?>
            <div class='form-group'>
                <label for='inputEmail3' class='col-sm-2 control-label'>任务量</label>
                <div class='col-sm-8'>
                    <input  name='TaskTemplateForm[num]' class='form-control' id='inputEmail3' value='<?= $model['num']?>'  placeholder=''>
                </div>
            </div>
            <div class='form-group'>
                <label for='inputEmail3'  class='col-sm-2 control-label'>过期时间</label>
                <div class='col-sm-8'>
                    <?= DateTimePicker::widget([
                        'name' => 'TaskTemplateForm[expire_time]',
                        'options' => ['placeholder' => '请输入任务截止时间'],
                        //注意，该方法更新的时候你需要指定value值
                        'value' => isset($model["expire_time"])? date('Y-m-d H:i:s', (int)$model["expire_time"]):null,
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
                    <input  name='TaskTemplateForm[job_remark]' class='form-control' id='inputEmail3'  value='<?= $model['job_remark']?>' placeholder=''>
                </div>
            </div>
            <div class="btn-group" style="margin-bottom: 20px;">
                <button type="submit" id = 'draft-button' class="btn btn-primary">草稿</button>
                <button type="reset"  class="btn btn-primary">重置</button>
                <button type='button' id="back_to_draft" class="btn btn-primary" href="<?= Url::to(['job/draft-index'])?>">返回</button>
            </div>

        </div>

        </form>
    </div>
</div>
</div>

<script type="text/javascript">
    $(function(){
        $('#back_to_draft').click(function(){
            var url = $(this).attr('href');
            window.location.href = url;
        })
    })

</script>

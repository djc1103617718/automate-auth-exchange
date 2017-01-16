<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\AppActionStep */

$this->title = Yii::t('app', '更新自动化');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化步骤'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css" xmlns="http://www.w3.org/1999/html">
</style>

<div class="app-action-step-create">

    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('创建自动化代号', 'btn btn-success', 'add')->link(['job-type/create', 'ref' => Url::to(['app-action-step/update', 'id' => $model->action_id])]);
    ButtonGroup::end();
    ?>
    <div class="app-action-step-form">

        <form id="w0" action=<?= Url::to(['app-action-step/update-process', 'id' => $model->step_id]) ?> method="post">
            <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
            <div class="form-group field-ActionStepForm-action_id required">
                <label class="control-label" for="ActionStepForm-action_id">动作ID</label>
                <input type="text" id="ActionStepForm-action_id" class="form-control" name="ActionStepForm[action_id]" value="<?= $model->action_id?>" readonly="readonly">

                <div class="help-block"></div>
            </div>
            <div class="form-group field-ActionStepForm-action_name">
                <label class="control-label" for="ActionStepForm-action_name">动作名</label>
                <input type="text" id="ActionStepForm-action_name" class="form-control" name="ActionStepForm[action_name]" value="<?= $model->action_name?>" readonly="readonly">

                <div class="help-block"></div>
            </div>
            <div class="form-group field-ActionStepForm-sort">
                <label class="control-label" for="ActionStepForm-sort">排序</label>
                <input type="text" id="ActionStepForm-sort" class="form-control" name="ActionStepForm[sort]" value="<?= $model->sort?>" >

                <div class="help-block"></div>
            </div>
            <div class="form-group field-ActionStepForm-step_symbol required">
                <label class="control-label" for="ActionStepForm-step_symbol">自动化步骤代号</label>
                <select id="ActionStepForm-step_symbol" class="form-control" name="ActionStepForm[step_symbol]">
                    <?php
                    foreach($actionStepArray as $k => $v) {
                        if ($k = $model->step_symbol) {
                            echo "<option value='$k' selected='selected'>" . $v . '(' . $k . ')'. "</option>";
                        } else {
                            echo "<option value='$k'>" . $v . '(' . $k . ')'. "</option>";
                        }
                    }
                    ?>
                </select>

                <div class="help-block"></div>
            </div>
            <div>
                <label class="control-label">自动化步骤参数️ <i style="color: #902b2b">(特殊符号请用英文输入法输入)</i></label>
                <table id='job_param'>
                    <tr id = 'test'>
                        <div class="form-inline">
                            <th class="line" style="text-align: center">输入框名称</th><th style="text-align: center" class="line">输入框类型</th><th style="text-align: center" class="line">数据类型</th><th style="text-align: center" class="line">字符长度</th><th style="text-align: center" class="line">数据大小</th><th style="text-align: center" class="line">数据枚举</th><th style="text-align: center">默认值</th><th style="text-align: center">为空</th><th style="text-align: center">排序</th><th style="text-align: center" class="line" colspan=4>操作</th>
                        </div>
                    </tr>
                    <?php
                        foreach ($inputArray as $key => $item ) {
                        //var_dump($inputArray);die;
                            echo "<tr name='step' id='$key' >";
                            echo "<div class='form-inline'>";
                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][input_box_name]' value='{$item["input_box_name"]}' /></td>";
                            echo "<td><select class='form-control' name='ActionStepForm[job_param][$key][input_box_type]' ><option>请选择...</option>";
                            foreach ($inputBoxTypeList as $inputBoxType) {
                                if ($item['input_box_type'] == $inputBoxType) {
                                    echo"<option value='$inputBoxType' selected='selected'> $inputBoxType</option>>";
                                } else {
                                    echo"<option value='$inputBoxType' >$inputBoxType</option>";
                                }
                            }
                            echo "</td>";
                            echo "<td><select class='form-control' name='ActionStepForm[job_param][$key][data_type]' ><option>请选择...</option>";
                            foreach ($inputDataTypeList as $inputDataType) {
                                if ($inputDataType == $item['data_type']) {
                                    echo"<option value='$inputDataType' selected='selected'> $inputDataType</option>>";
                                } else {
                                    echo"<option value='$inputDataType' >$inputDataType</option>";
                                }
                            }
                            echo "</td>";
                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][data_length]' value='{$item["data_length"]}' placeholder='如:[0,100]或[1,+∞)' /></td>";
                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][data_size]' value='{$item["data_size"]}' placeholder='如:[0,100]或[1,+∞)' /></td>";
                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][data_list]' value='{$item['data_list']}'  placeholder='label=>value或者0,1,2,3' /></td>";

                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][default]' placeholder='默认值' value='{$item["default"]}'></td>";
                            echo "<td>";
                            echo "<select class='form-control' name='ActionStepForm[job_param][$key][allowed_null]' >";
                                    if ($item['allowed_null'] == 2) {
                                        echo "<option value=2 selected='selected'>否&nbsp;</option>";
                                        echo "<option value=1>是&nbsp;</option>";
                                    } else {
                                        echo "<option value=1 selected='selected'>是&nbsp;</option>";
                                        echo "<option value=2>否&nbsp;</option>";
                                    }

                            echo '</select></td>';
                            echo "<td><input class='form-control' name='ActionStepForm[job_param][$key][sort]' placeholder='值越大,越靠前显示'></td>";

                            if ($key == 'arg1') {
                                echo "<td><a class='addLine'>&nbsp;<span class='fa fa-plus'></span>&nbsp;<a/></td><td><a class='deleteLine'>&nbsp;<span class='fa fa-minus'></span></a></td>";
                            }
                            echo "</div>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>

            <div class="form-group">
                <button type="submit" id = 'create-step' class="btn btn-success">提交</button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    $(function(){
        $(document).on('click','.addLine', function(){
            var idNumber = $("#job_param").children().children("tr:last-child").attr('id').split('arg')[1];
            var nextIdNumber = idNumber*1 + 1*1;
            var next_id = 'arg' + nextIdNumber;
            var box_type_options = "<?php if ($inputBoxTypeList) {
                foreach ($inputBoxTypeList as $item) {
                    echo "<option value='$item' > $item </option>";
                }
            }?>";
            var data_type_options = "<?php if ($inputDataTypeList) {
                foreach ($inputDataTypeList as $item) {
                    echo "<option value='$item' > $item </option>";
                }
            } ?>";
            var data_default = "<?php
                echo "<option value=2 selected='selected'>否&nbsp;</option>" . "<option value=1>是&nbsp;</option>";
            ?>";

            var next_input =
                "<tr name='step' id=" + next_id + ">" +
                "<div class='form-inline'>" +
                "<td><input class='form-control' name=ActionStepForm[job_param][" + next_id + "][input_box_name] /></td>" +
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][input_box_type]  ><option>请选择...</option>"+
                box_type_options + "</select></td>" +
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][data_type]  ><option>请选择...</option>"+
                data_type_options + "</select></td>" +
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][data_length]  placeholder='如:[0,100]或[1,+∞)'/></td>"+
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][data_size]  placeholder='如:[0,100]或[1,+∞)'/></td><td><input class='form-control' name=ActionStepForm[job_param][" + next_id + "][data_list] placeholder='女=>2,男=>1或者0,1,2,3' /></td>" +
                "<td><input class='form-control' name='ActionStepForm[job_param]["+ next_id +"][default]' placeholder='默认值'></td>"+
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][allowed_null]  >"+
                data_default + "</select></td>" +
                "<td><input class='form-control' name='ActionStepForm[job_param][" + next_id + "][sort]' placeholder='值越大,越靠前显示'></td>"+
                "</div>" +
                "</tr>";
            $('#arg' + idNumber).parent().append(next_input);

        });

        $('.deleteLine').click(function () {
            if ($('#job_param').find('tr:last-child').attr('id') == 'arg1') {
                return false;
            }
            $('#job_param').find('tr:last-child').remove();
        });

    })

</script>

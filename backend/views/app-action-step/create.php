<?php

use yii\helpers\Url;
use common\helper\views\ButtonGroup;

/* @var $this yii\web\View */
/* @var $model backend\models\AppActionStep */

$this->title = Yii::t('app', '自动化步骤创建');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '自动化步骤'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css" xmlns="http://www.w3.org/1999/html">
</style>

<div class="app-action-step-create">
    <?php
    $btn = ButtonGroup::begin();
    $btn->buttonDefault('创建自动化代号', 'btn btn-success', 'add')->link(['job-type/create', 'ref' => Url::to(['app-action-step/create', 'id' => $model->action_id])]);
    ButtonGroup::end();
    ?>
    <div class="app-action-step-form">

        <form id="w0" action=<?= Url::to(['app-action-step/create-process'])?> method='post'>
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
            <div class="form-group field-ActionStepForm-step_symbol required">
                <label class="control-label" for="ActionStepForm-step_symbol">自动化步骤代号</label>
                <select id="ActionStepForm-step_symbol" class="form-control" name="ActionStepForm[step_symbol]">
                    <?php
                    foreach($actionStepArray as $k => $v) {
                        echo "<option value=" . $k . ">" . $v . '(' . $k . ')' . "</option>";
                    }
                    ?>
                </select>

                <div class="help-block"></div>
            </div>
            <div class="form-group field-ActionStepForm-sort">
                <label class="control-label" for="ActionStepForm-sort">排序</label>
                <input type="text" id="ActionStepForm-sort" class="form-control" name="ActionStepForm[sort]" value="<?= $model->sort?>">

                <div class="help-block"></div>
            </div>
            <div>
                <label class="control-label">自动化步骤参数⚠️ <i style="color: #902b2b">(','、'>'、'<'、'='等特殊符号请用英文输入法输入!)</i></label>
                <table id='job_param'>

                    <tr id = 'test'>
                        <div class="form-inline">
                            <th class="line" style="text-align: center">输入框名称</th><th class="line" style="text-align: center">输入框类型</th><th class="line" style="text-align: center">数据类型</th><th class="line" style="text-align: center">字符长度</th><th class="line" style="text-align: center">数据大小</th><th class="line" style="text-align: center">数据枚举</th><th class="line" style="text-align: center">默认值</th><th class="line" style="text-align: center">空</th><th class="line" style="text-align: center">排序</th><th class="line" colspan="2" style="text-align: center">操作</th>
                        </div>
                    </tr>
                    <tr name="step" id="arg1">
                        <div class="form-inline">
                            <td>
                                <input class="form-control" name="ActionStepForm[job_param][arg1][input_box_name]" placeholder="输入框名称">
                            </td>
                            <td>
                                <select class="form-control" name='ActionStepForm[job_param][arg1][input_box_type]' >
                                    <option value="">请选择...</option>
                                    <?php if ($inputBoxTypeList) {
                                        foreach ($inputBoxTypeList as $item) {
                                            echo "<option value='$item' > $item </option>";
                                        }
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name='ActionStepForm[job_param][arg1][data_type]' >
                                    <option value="">请选择...</option>
                                    <?php if ($inputDataTypeList) {
                                        foreach ($inputDataTypeList as $item) {
                                            echo "<option value='$item' > $item </option>";
                                        }
                                    } ?>
                                </select>
                            </td>
                            <td>
                                <input class="form-control" name='ActionStepForm[job_param][arg1][data_length]' placeholder="如:[0,100]或[1,+∞)">
                            </td>
                            <td>
                                <input class="form-control" name='ActionStepForm[job_param][arg1][data_size]' placeholder="如:[0,100]或[1,+∞)">
                            </td>
                            <td>
                                <input class="form-control" name='ActionStepForm[job_param][arg1][data_list]' placeholder="女=>2,男=>1或者1,2,3">
                            </td>

                            <td>
                                <input class="form-control" name='ActionStepForm[job_param][arg1][default]' placeholder="默认值">
                            </td>
                            <td>
                                <select class="form-control" name="ActionStepForm[job_param][arg1][allowed_null]" >
                                    <option value=2 selected="selected">否&nbsp;</option>
                                    <option value=1>是&nbsp;</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control" name='ActionStepForm[job_param][arg1][sort]' placeholder='值越大,越靠前显示'>
                            </td>

                           <td>
                               <a class='addLine'>&nbsp;<span class='fa fa-plus'></span>&nbsp;<a/></td><td><a class='deleteLine'><span class='fa fa-minus'></span>&nbsp;<a/>
                            </td>

                            </div>

                        <td>

                    </tr>
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
            var next_input =
                "<tr name='step' id=" + next_id + ">" +
                "<div class='form-inline'>" +
                "<td><input class='form-control' name=ActionStepForm[job_param][" + next_id + "][input_box_name] placeholder='输入框名称'/></td>" +
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][input_box_type]  ><option value=''>请选择...</option>"+
                box_type_options + "</select></td>" +
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][data_type]  ><option value=''>请选择...</option>"+
                data_type_options + "</select></td>" +
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][data_length]  placeholder='如:[0,100]或[1,+∞)'/></td>" +
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][data_size]  placeholder='如:[0,100]或[1,+∞)'/></td>" +
                "<td><input class='form-control' name=ActionStepForm[job_param][" + next_id + "][data_list] placeholder='label=>value或者0,1,2,3'/></td>"+
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][default]  placeholder='默认值'/></td>"+
                "<td><select class='form-control' name=ActionStepForm[job_param][" + next_id + "][allowed_null]  ><option value=2>否&nbsp;</option>"+
                "<option value=2>是&nbsp;</option>" + "</select></td>" +
                "<td><input class='form-control' name=ActionStepForm[job_param]["+ next_id +"][sort]  placeholder='值越大,显示越靠前'/></td>"+
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

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\AppDetailForm */
/* @var $form ActiveForm */
?>
<p>
    <?= Html::a(Yii::t('app', '创建自动化代号'), ['job-type/create', 'ref' => Url::to(['app/detail-update', 'app_id' => $model->app_id,'action_id' => $model->action_id, 'step_id' => $model->step_id])], ['class' => 'btn btn-primary']) ?>
</p>
<div class="detail-update">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'app_id')->input('text',['readonly' => 'readonly']) ?>
    <?= $form->field($model, 'app_name') ?>
    <?= $form->field($model, 'package_name') ?>
    <?= $form->field($model, 'search_name') ?>
    <?= $form->field($model, 'action_id')->input('text',['readonly' => 'readonly']) ?>
    <?= $form->field($model, 'status')->input('text',['readonly' => 'readonly']) ?>
    <?= $form->field($model, 'action_name') ?>
    <?= $form->field($model, 'action_class_name') ?>
    <?= $form->field($model, 'category') ?>
    <?= $form->field($model, 'action_status')->input('text',['readonly' => 'readonly']) ?>
    <?= $form->field($model, 'step_id')->input('text',['readonly' => 'readonly']) ?>
    <div class="form-group field-AppDetailForm-step_symbol required">
        <label class="control-label" for="AppDetailForm-step_symbol">自动化步骤代号</label>
        <select id="AppDetailForm-step_symbol" class="form-control" name="AppDetailForm[step_symbol]">
            <?php if ($actionStepArray) {
                $flag = false;
                foreach ($actionStepArray as $k => $item) {
                    if ($k == $model->step_symbol) {
                        echo "<option value= '$k'  selected='selected'>$item($k)</option>";
                        $flag = true;
                    } else {
                        echo "<option value='$k'>$item($k)</option>";
                    }
                }
                if (!$flag) {
                    Yii::$app->session->setFlash('error', '自动化代号暂未与app关联');
                }
            }?>
        </select>

        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'step_status')->input('text',['readonly' => 'readonly']) ?>
    <div>
        <label class="control-label">自动化步骤参数⚠️ <i style="color: #902b2b">(','、'>'、'<'、'='等特殊符号请用英文输入法输入和'<='、'>='别用错!)</i></label>
        <table id='job_param'>
            <tr id = 'test'>
                <th class="line">输入框名称</th><th class="line">输入框类型</th><th class="line">用户输入数据类型</th><th class="line">数据范围</th><th class="line">数据枚举</th><th class="line">添加</th><th></th><th class="line">删除</th>
            </tr>
            <?php
            foreach ($inputArray as $key => $item ) {
                echo "<tr name='step' id='$key' >";
                echo "<td><input name='AppDetailForm[job_param][$key][input_box_name]' value='$item->input_box_name' /></td>";
                echo "<td><select name='AppDetailForm[job_param][$key][input_box_type]' ><option value=''>请选择输入框类型...</option>";
                foreach ($inputBoxTypeList as $inputBoxType) {
                    if ($item->input_box_type == $inputBoxType) {
                        echo"<option value='$inputBoxType' selected='selected'> $inputBoxType</option>>";
                    } else {
                        echo"<option value='$inputBoxType' >$inputBoxType</option>";
                    }
                }
                echo "</td>";
                echo "<td><select name='AppDetailForm[job_param][$key][data_type]' ><option value=''>请选择输入框类型...</option>";
                foreach ($inputDataTypeList as $inputDataType) {
                    if ($inputDataType == $item->data_type) {
                        echo"<option value='$inputDataType' selected='selected'> $inputDataType</option>>";
                    } else {
                        echo"<option value='$inputDataType' >$inputDataType</option>";
                    }
                }
                echo "</td>";
                echo "<td><input name='AppDetailForm[job_param][$key][data_constraint]' value='$item->data_constraint' placeholder='100>$$>=0' /></td>";
                echo "<td><input name='AppDetailForm[job_param][$key][data_list]' value='$item->data_list'  placeholder='label=>value或者0,1,2,3' /></td>";
                if ($key == 'arg1') {
                    echo "<td><a class='addLine'>&nbsp;+&nbsp;<a/></td><td><a class='deleteLine'>&nbsp;-</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
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
                "<td><input name=AppDetailForm[job_param][" + next_id + "][input_box_name] /></td>" +
                "<td><select name=AppDetailForm[job_param][" + next_id + "][input_box_type]  ><option value=''>请选择输入框类型...</option>"+
                box_type_options + "</select></td>" +
                "<td><select name=AppDetailForm[job_param][" + next_id + "][data_type]  ><option value=''>请选择数据类型...</option>"+
                data_type_options + "</select></td>" +
                "<td><input name=AppDetailForm[job_param]["+ next_id +"][data_constraint]  placeholder='100>$$>=0'/></td><td><input name=AppDetailForm[job_param][" + next_id + "][data_list] placeholder='女=>2,男=>1或者0,1,2,3' /></td>" +
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

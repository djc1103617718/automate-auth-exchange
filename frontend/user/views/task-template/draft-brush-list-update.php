<?php

use yii\helpers\Url;
use yii\bootstrap\Html;
?>

<p><h1><?= $app['app_name'];?></h1></p>
<a class="btn btn-primary" href="<?= Url::to(['task-template/app'])?>">返回</a>


<form id='job_create_form' action=" <?= Url::to(['draft-update', 'id' => $model['job_id']])?> " method='post'>
    <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
    <input type="hidden" name="TaskTemplateForm[job_id]" value="<?=$model['job_id'] ?>">
    <div 'id'='inputContainer'>
    <?php
    if ($inputArray) {
        foreach ($inputArray as $key => $item) {
            ?>
            <?php
            if ($item->input_box_type == 'input') {
                echo"<p><label>$item->input_box_name</label><input name='TaskTemplateForm[job_params][$key]'/></p>";
            } elseif ($item->input_box_type == 'select') {
                $listArray = explode(',', $item->data_list);
                if (!$listArray) {
                    throw new ErrorException('select 输入框缺少必要的枚举值');
                }
                echo"<p><label>$item->input_box_name</label><select name='TaskTemplateForm[job_params][$key]'>";
                echo "<option value=''>请选择...</option>";
                foreach ($listArray as $v) {
                    echo"<option value='{$v}'>$v</option>";
                }
                echo"</select></p>";
            }
            ?>
            <?php
        }
    }
    ?>

    </div>
    <div>
        <?php if ($actionPrice) {
            foreach ($actionPrice as $key => $item) {
                echo '<p><label>' . $item['label'] . '</label>';
                echo "<select name='TaskTemplateForm[$key]'>";
                echo "<option value='' >请选择...</option>";
                foreach ($item['values'] as $k => $v) {
                    echo "<option value='$v'>$v</option>";
                }
                echo '</select></p>';
            }
        }?>
            </select>
        </p>
        <p><label>任务量</label><input name='TaskTemplateForm[num]' value=<?= $model['num']?> /></p>
        <p><label>过期时间</label><input name='TaskTemplateForm[expire_time]' <?= $model['expire_time']?> /></p>
        <p><label>任务备注</label><input type="text" name='TaskTemplateForm[job_remark]' <?= $model['job_remark']?> /></p>

    </div>
    <div class="form-group">
        <button type="submit" id = 'draft-button' class="btn btn-info">草稿</button>
        <button type="reset"  class="btn btn-success">重置</button>
    </div>
</form>

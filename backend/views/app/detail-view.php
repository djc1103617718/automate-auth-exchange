<?php
use yii\helpers\Url;
use common\helper\views\ButtonGroup;

$this->title = '自动化步骤完整详情';

?>
    <div class="container">
        <ul class="breadcrumb"><li><a href="<?= Url::to(['/'])?>">Home</a></li>
            <li><a href="<?= Url::to(['detail-index'])?>">App详情列表</a></li>
            <li class="active"><?=$this->title?></li>
        </ul>
        <div class="app-view">
            <?php
            $button = ButtonGroup::begin();
            $button->buttonDefault('创建APP', 'btn btn-success', 'add')->link(['app-action/create', 'id' => $model['app_id']]);
            $button->buttonDefault('增加自动化步骤', 'btn btn-primary', 'add2')->link(['app-action-step/create', 'id' => $model['action_id']]);
            //$button->buttonDefault('更新自动化', 'btn btn-warning', 'update')->link(['app-action/update', 'id' => $model->step_id]);
            $button->buttonDefault('删除APP', 'btn btn-danger', 'delete')->confirm([
                'url' => 'app/delete',
                'title' => '删除APP!',
                'content' => '删除该APP平台,这将无法创建该平台下的所有任务,你确定要删除吗?',
                'data' => ['id' => $model['app_id']],
            ]);
            if ($model['action_id'] && !$model['step_id']) {
                $button->buttonDefault('创建该动作的自动化步骤', 'btn btn-success', 'add')->link(['app-action-step/create', 'action_id' => $model['action_id']]);
            }
            ButtonGroup::end();
            ?>

            <table id="w0" class="table table-striped table-bordered detail-view">
                <tr><th colspan="2" style="color: darkorange">APP</th></tr>
                <tr><th>App ID</th><td><?= $model['app_id']?></td></tr>
                <tr><th>App名称</th><td><?= $model['app_name']?></td></tr>
                <tr><th>App软件包名</th><td><?= $model['package_name']?></td></tr>
                <tr><th>App可搜索名</th><td><?= $model['search_name']?></td></tr>
                <tr><th>App状态</th><td><?= \backend\models\App::getStatusName($model['status'])?></td></tr>
                <tr><th>App创建时间</th><td><?= date('Y-m-d H:i:s', $model['created_time'])?></td></tr>
                <tr><th>App更新时间</th><td><?= date('Y-m-d H:i:s', $model['updated_time'])?></td></tr>
                <?php
                if ($model['action_id']) {
                ?>
                <tr><th colspan="2" style="color: darkorange">动作</th></tr>
                <tr><th>Action ID</th><td><?= $model['action_id']?></td></tr>
                <tr><th>动作名称</th><td><?= $model['action_name']?></td></tr>
                <tr><th>分类</th><td><?= \yii\helpers\ArrayHelper::getValue(\backend\models\AppAction::categoryList(), $model['category'])?></td></tr>
                <tr><th>动作价格类名</th><td><?= $model['action_class_name']?></td></tr>
                <tr><th>动作状态</th><td><?= \backend\models\AppAction::getStatusName($model['action_status'])?></td></tr>
                <tr><th>动作创建时间</th><td><?= \backend\models\AppAction::getStatusName($model['action_created_time'])?></td></tr>
                <tr><th>动作更新时间</th><td><?= \backend\models\AppAction::getStatusName($model['action_updated_time'])?></td></tr>
                <?php } else {?>
                <a class="btn btn-primary" style="display: block" href="<?= Url::to(['app-action/create', 'app_id' => $model['app_id'] ])?>">创建该App动作</a>
                <?php }?>

                <?php
                if ($model['step_id']) {
                ?>
                <tr><th colspan="2" style="color: darkorange">自动化步骤</th></tr>
                <tr><th>Step ID</th><td><?= $model['step_id']?></td></tr>
                <tr><th>自动化步骤代号</th><td>
                <?php
                    $job_type = \backend\models\JobType::findOne(['step_symbol' => $model['step_symbol']]);
                    if ($job_type) {
                        echo $job_type->job_type_name . '('. $model['step_symbol'] .')';
                    } else {
                    echo '('. $model['step_symbol'] .')not set';
                    }
                ?></td></tr>
                <tr><th>自动化步骤参数</th><td><?= $model['job_param']?></td></tr>
                <tr><th>自动化步骤状态</th><td><?= \backend\models\AppActionStep::getStatusName($model['step_status'])?></td></tr>
                <tr><th>自动化创建时间</th><td><?= date('Y-m-d H:i:s', $model['step_created_time'])?></td></tr>
                <tr><th>自动化更新时间</th><td><?= date('Y-m-d H:i:s', $model['step_updated_time'])?></td></tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>


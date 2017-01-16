<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<style type="text/css">
    #title{color: #017ebc; background-color: #f5f5f5; padding: 8px 10px;margin-top: 0px;margin-bottom: 2px}
    #content-header{margin-bottom: 0px;}
    #breadcrumb .breadcrumb{float: left;margin-bottom:2px; padding-right:5px;padding-top:2px;padding-bottom: 2px;padding-right: 15px;}
</style>
<div class="content-wrapper" style="overflow-y: auto;margin-top: 42px;">
    <section class="content-header" id="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h3 id='title'><?= $this->blocks['content-header'] ?></h3>
        <?php } else { ?>
            <h3 id="title">
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo /*($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : */'';
                } ?>
            </h3>
        <?php } ?>
        <div id="breadcrumb" >
            <?=
            Breadcrumbs::widget(
                [
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]
            ) ?>
        </div>

    </section>
    <section class="content">
        <div style="clear: both">
        <?= Alert::widget() ?>
        </div>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
    reserved.
</footer>

<!-- Control Sidebar -->
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
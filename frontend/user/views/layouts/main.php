<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('frontend\assets\AppAsset')) {
        frontend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>
        <div style="overflow-x: auto">
        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
<script type="text/javascript">
    $('#draft-delete-all').on('click', function(){
        var d = dialog({
            title: '清空草稿箱',
            content: '你想要清空草稿箱吗?',
            okValue: '是',
            ok: function () {//回调函数
                var that = this;
                setTimeout(function () {
                    that.title('提交中..');
                }, 1000);
                window.location.href = "<?= \yii\helpers\Url::to(['job/draft-delete-all'])?>";
            },
            cancelValue: '否',
            cancel: function () {//回调函数

            }
        });

        d.show();
    });

    function confirmDelete(url, data=null, title='删除提醒!', content='删除以后将无法找回,你确定要删除吗?' ) {
        if (data){
            data = eval('(' + data + ')');
        }
        var d = dialog({
            title: title,
            content: content,
            okValue: '是',
            ok: function () {//回调函数
                $.ajax({
                    url: url,
                    dataType: "json",
                    method:'post',
                    data: data,
                    success: function (data) {
                    }
                });
            },
            cancelValue: '否',
            cancel: function () {//回调函数

            }
        });
        d.show();
    }

    function link(url) {
        window.location.href = url;
    }
</script>

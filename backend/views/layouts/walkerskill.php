<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">WalkerSkill</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php
                    echo \backend\widgets\NavMenuWidget::widget([]);
                ?>


                <ul class="nav navbar-nav navbar-right" ng-controller="loginController">
                    <?php if (!Yii::$app->user->isGuest) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php echo Yii::$app->user->identity->username ?><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">User Menu 1</a></li>
                            <li><a href="#">User Menu 2</a></li>
                            <li><a href="#">User Menu 3</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" class="logout">logout</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar" style="width: 15%;padding-top:60px;float: left">
        <?php
            echo \backend\widgets\SideMenuWidget::widget();
        ?>
    </div>

    <div class="container" style="width: 75%;padding-top:60px;padding-left:30px;float: left">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
<script>
    $(function(){
        $('.logout').on('click',function(){
            $.ajax({
                url: '/index.php?r=site/logout',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '<?php echo Yii::$app->getRequest()->getCsrfToken()?>'
                },
                success: function (data) {
                    location.reload();
                }
            });
        });
    })
</script>

<script type="text/javascript">
    jQuery("#jquery-accordion-menu").jqueryAccordionMenu();
</script>
</html>
<?php $this->endPage() ?>

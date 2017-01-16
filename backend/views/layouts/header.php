<?php
use yii\helpers\Html;
use backend\models\Notice;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>
<style type="text/css">
    .skin-blue .main-header .navbar {
        background-color: #e6e7e8;
    }
</style>
<header class="main-header" style="position: fixed;width:100%;">

    <?= Html::a('<span class="logo-mini">ZDH</span><span class="logo-lg">'.'自动化管理中心' /*Yii::$app->name*/ . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <?php
            $noticeModel = Notice::find()
                ->select(['notice_id', 'title'])
                ->where(['category_name' => Notice::getServerNoticeCategory(), 'status' => Notice::STATUS_UNREAD])
                ->orderBy(['created_time' => SORT_DESC])
                ->asArray()->all();
            $num = count($noticeModel);

            ?>
            <ul class="nav navbar-nav">

                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o text-orange" style="font-size: 18px"></i>
                        <span class="label label-warning"><?php $num = empty($num)? '': $num; echo $num?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header text-orange text-bold"><?php $msg=(!$num)?'您没有新消息!':"您有{$num}条新消息!"; echo $msg ?></li>
                        <?php
                        if ($noticeModel) {
                            foreach ($noticeModel as $item) {
                                $url = Url::to(['notice/view', 'id' => $item['notice_id']]);
                                echo "<li class='text text-blue'><a style='color:orange' href= '$url'>";
                                echo "<i class='fa fa-envelope-o text-orange'></i>{$item['title']}</a></li>";
                            }

                        }
                        ?>
                        <li style="text-align: center;margin-top: 5px; margin-bottom: 5px;"><a href="<?=Url::to(['notice/index'])?>">查看所有</a></li>
                    </ul>
                </li>
                <li class="dropdown notifications-menu">
                    <a href="<?=Yii::$app->homeUrl?>">
                        <!--<i class="glyphicon glyphicon-home text-dark" style="font-size: 20px">--></i><i class="glyphicon glyphicon-home" style="font-size: 20px; color: #3c8dbc"></i>
                        <span class="label label-default"></span>
                    </a>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <?php if (isset(Yii::$app->user->id)) {?>
                    <form action=<?= \yii\helpers\Url::to(['site/logout'])?> method='post'>
                        <input type="hidden" name="backend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
                        <button type="submit" class="btn btn-default" ><span class='fa fa-sign-out text-info'>(<?=Yii::$app->user->identity->username?>)</span></button>
                    </form>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </nav>
</header>

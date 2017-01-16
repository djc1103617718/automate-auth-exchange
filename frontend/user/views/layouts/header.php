<?php
use yii\helpers\Html;
use frontend\models\Notice;
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

    <?= Html::a('<span class="logo-mini">USER</span><span class="logo-lg">' . '用户管理中心'/*Yii::$app->name*/ . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <!--<li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="<?/*= $directoryAsset */?>/img/user3-128x128.jpg" class="img-circle"
                                                 alt="user image"/>
                                        </div>
                                        <h4>
                                            Sales Department
                                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>-->

                <?php
                    $noticeModel = Notice::find()
                        ->select(['notice_id', 'title'])
                        ->where(['user_id' => Yii::$app->user->id, 'status' => Notice::STATUS_UNREAD])
                        ->asArray()->all();
                        $num = count($noticeModel);

                ?>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o text-orange" style="font-size: 18px"></i>
                        <span class="label label-danger"><?php $num = empty($num)? '': $num; echo $num?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header text-orange text-bold"><i class="fa fa-hand-o-down"> </i> <?php $msg=(!$num)?'您没有新消息!':"您有{$num}条新消息!"; echo $msg ?></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                if ($noticeModel) {
                                    foreach ($noticeModel as $item) {
                                        $url = Url::to(['notice/view', 'id' => $item['notice_id']]);
                                        echo "<li class='text text-blue'><a style='color:orange' href= '$url'>";
                                        echo "<i class='fa fa-envelope-o text-orange'></i>{$item['title']}</a></li>";
                                    }

                                }
                                ?>
                                <!--<li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>-->
                            </ul>
                        </li>
                        <li style="text-align: center;margin-top: 5px; margin-bottom: 5px;"><a href="<?=Url::to(['notice/index'])?>">查看所有消息</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
               <!-- <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <h3>
                                            Make beautiful transitions
                                            <small class="pull-right">80%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                 role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
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
                        <form action=<?= \yii\helpers\Url::to(['/site/logout'])?> method='post'>
                            <input type="hidden" name="frontend_csrf" value="<?=Yii::$app->request->getCsrfToken() ?>">
                            <button type="submit" class="btn btn-default" ><span class='fa fa-sign-out text-info'>(<?=Yii::$app->user->identity->username?>)</span></button>
                        </form>
                    <?php } ?>
                </li>
                <!-- User Account: style can be found in dropdown.less -->

            </ul>
        </div>
    </nav>
</header>

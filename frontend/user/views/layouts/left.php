<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="font-size: 18px;">
            <div class="pull-left image">
                <!--<img src="<?/*= $directoryAsset */?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>-->
            </div>
            <div class="pull-left info">
                <!--<p><?/*=\frontend\models\User::findOne(['id' => Yii::$app->user->id, 'status' => \frontend\models\User::STATUS_ACTIVE])->username*/?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
            </div>
        </div>

        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    [
                        'label' => '商务中心',
                        'icon' => 'fa fa-building',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '创建任务', 'icon' => 'fa fa-edit', 'url' => ['/user/task-template/app'], 'options' => ['style' => 'font-size:16px']],
                        ]
                    ],
                    [
                        'label' => '任务中心',
                        'icon' => 'fa fa-th-list',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '审核失败任务', 'icon' => 'glyphicon glyphicon-remove-circle', 'url' => ['/user/job/cancel-index'],'options' => ['style' => 'font-size:16px'],],
                            ['label' => '待审核任务', 'icon' => 'fa fa-circle-o-notch', 'url' => ['/user/job/new-index'],'options' => ['style' => 'font-size:16px'],],
                            ['label' => '待执行任务', 'icon' => 'fa fa-clock-o', 'url' => ['/user/job/awaiting-index'],'options' => ['style' => 'font-size:16px'],],
                            ['label' => '执行中任务', 'icon' => 'fa fa-circle-thin', 'url' => ['/user/job/executing-index'],'options' => ['style' => 'font-size:16px'],],
                            ['label' => '已完成任务', 'icon' => 'fa fa-check-circle', 'url' => ['/user/job/complete-index'],'options' => ['style' => 'font-size:16px'],],
                            ['label' => '所有任务', 'icon' => 'fa fa-circle-o', 'url' => ['/user/job/index'],'options' => ['style' => 'font-size:16px'],],
                        ],
                    ],
                    [
                        'label' => '消息中心',
                        'icon' => 'fa fa-commenting',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '新消息', 'icon' => 'fa fa-bell' ,'url' => ['/user/notice/new-index'],],
                            ['label' => '所有消息', 'icon' => 'fa fa-bell-o', 'url' => ['/user/notice/index'],],
                        ]
                    ],
                    [
                        'label' => ' 账户中心',
                        'icon' => 'fa fa-user',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '资金记录', 'icon' => 'fa fa-bar-chart', 'url' => ['/user/funds-record/index'],],
                            ['label' => '账户信息', 'icon' => 'fa fa-user-secret', 'url' => ['/user/user/view']],
                            ['label' => '账户更新', 'icon' => 'fa fa-gear', 'url' => ['/user/user/update']],
                            [
                                'label' => '安全中心',
                                'icon' => 'fa fa-shield',
                                'url' => '#',
                                'items' =>
                                    [
                                        ['label' => '密码重置', 'icon' => 'fa fa-eraser', 'url' => ['/user/user/request-password-reset']],
                                        ['label' => '手机绑定', 'icon' => 'fa fa-mobile', 'url' => ['/user/user/phone-bind']],
                                        ['label' => '邮箱更新', 'icon' => 'fa fa-envelope-square', 'url' => ['/user/user/request-email-reset']],
                                    ]
                            ],

                        ],
                    ],

                    [
                        'label' => '草稿箱',
                        'icon' => 'fa fa-suitcase',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        //'options' => ['id' => 'draft'],
                        'items' => [
                            ['label' => '创建任务草稿', 'icon' => 'fa fa-pencil-square', 'url' => ['/user/task-template/draft-app']],
                            ['label' => '任务草稿', 'icon' => 'fa fa-list-alt', 'url' => ['/user/job/draft-index']],
                            ['label' => '清空草稿箱', 'icon' => 'fa fa-trash', 'url' => '#', 'options' => ['id' => 'draft-delete-all'],],
                        ],
                    ],
                    //['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    //['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                ],
            ]
        ) ?>
    </section>

</aside>

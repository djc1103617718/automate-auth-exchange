<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
       <!-- <div class="user-panel">
            <div class="pull-left image">
                <img src="<?/*= $directoryAsset */?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->

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
                        'label' => '任务管理',
                        'icon' => 'fa fa-th-list',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '创建任务', 'url' => ['task-template/app'],],
                            [
                                'label' => '任务列表',
                                'url' => '#',
                                'items' => [
                                    ['label' => '新任务', 'url' => ['job/new-index'],],
                                    ['label' => '待执行任务', 'url' => ['job/awaiting-index'],],
                                    ['label' => '执行中任务', 'url' => ['job/executing-index'],],
                                    ['label' => '已完成任务', 'url' => ['job/complete-index'],],
                                    ['label' => '审核失败任务', 'url' => ['job/cancel-index'],],
                                    ['label' => '草稿箱', 'url' => ['job/draft-index'],],
                                    ['label' => '所有任务', 'url' => ['job/index'],],
                                ],
                            ],
                            //['label' => '创建草稿', 'url' => ['task-template/draft-app'],],
                        ],
                    ],
                    [
                        'label' => '设备管理', 'url' => '#',
                        'icon' => 'glyphicon glyphicon-phone',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '设备列表', 'url' => ['device/device-index']],
                            [
                                'label' => '微信设备', 'url' => ['#'],
                                'items' => [
                                    ['label' => '设备下的微信', 'url' => ['device/index'],],
                                ],
                            ],
                            ['label' => '设备任务日志', 'url' => ['device-log/index'],],
                        ],
                    ],
                    [
                        'label' => '账号管理',
                        'icon' => 'fa fa-indent',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            [
                                'label' => '微信账号',
                                'url' => '#',
                                'items' => [
                                    ['label' => '微信账号列表', 'url' => ['we-chat/index'],],
                                    ['label' => '账号任务日志', 'url' => ['account-job-log/index'],],
                                    ['label' => '微信在线时长', 'url' => ['wechat-online-time/index'],],
                                ],
                            ],
                            [
                                'label' => '账号注册统计',
                                'url' => '#',
                                'items' => [
                                    //['label' => '360账号列表', 'url' => ['user-qihu360-mobile/index'],],
                                    ['label' => '每日账号统计', 'url' => ['register-daily-statistics/index'],],
                                    [
                                        'label' => '当下注册与登录',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => '360账号', 'url' => ['register-daily-statistics/current360'],],
                                            ['label' => '壁纸账号', 'url' => ['register-daily-statistics/current-bizhi'],],
                                        ],
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => 'VPN 管理',
                        'icon' => 'fa fa-random',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => 'VPN列表', 'url' => ['vpn/index'],],
                            ['label' => 'VPN使用日志', 'url' => ['vpn-usage/index'],],
                            ['label' => 'VPN重复率', 'url' => ['vpn-repeat/index'],],
                        ],
                    ],
                    [
                        'label' => '内容管理',
                        'icon' => 'fa fa-folder-open',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            [
                                'label' => '微博抓取内容',
                                'url' => '#',
                                'items' => [
                                    ['label' => '未审核内容', 'url' => ['content-weibo/awaiting-index'],],
                                    ['label' => '通过审核内容', 'url' => ['content-weibo/success-index'],],
                                    ['label' => '审核失败内容', 'url' => ['content-weibo/failure-index'],],
                                    ['label' => '所有内容', 'url' => ['content-weibo/index'],],
                                ],
                            ],
                            [
                                'label' => '抓取图片',
                                'url' => '#',
                                'items' => [
                                    ['label' => '未审核图片', 'url' => ['pic-renren/awaiting-index'],],
                                    ['label' => '通过审核图片', 'url' => ['pic-renren/success-index'],],
                                    ['label' => '审核失败图片', 'url' => ['pic-renren/failure-index'],],
                                    ['label' => '所有图片', 'url' => ['pic-renren/index'],],
                                ],
                            ],
                        ]
                    ],
                    [
                        'label' => '用户管理',
                        'icon' => 'fa fa-users',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '用户列表', 'url' => ['user/index'],],
                            [
                                'label' => 'VIP管理',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'VIP列表', 'url' => ['vip/index'],],
                                    ['label' => '创建VIP', 'url' => ['vip/create'],],
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => '消息管理',
                        'icon' => 'fa fa-commenting',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '新消息', 'url' => ['notice/new-index'],],
                            ['label' => '所有消息', 'url' => ['notice/index'],],
                            ['label' => '消息分类管理', 'url' => '#',
                                'items' => [
                                    ['label' => '所有分类', 'url' => ['notice-category/index'],],
                                    ['label' => '创建类别', 'url' => ['notice-category/create'],],
                                ]
                            ],

                        ]
                    ],

                    [
                        'label' => '资金管理',
                        'icon' => 'fa fa-bar-chart',
                        'url' => '',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '资金记录', 'url' => ['funds-record/index'],],
                        ]
                    ],

                    [
                        'label' => 'APP 管理',
                        'icon' => 'fa fa-cubes',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            [
                                'label' => 'APP应用', 'url' => '#',
                                'items' => [
                                    ['label' => 'APP列表', 'url' => ['app/index'],],
                                ],
                            ],
                            [
                                'label' => 'APP动作',
                                'url' => ['#'],
                                'items' => [
                                    ['label' => 'APP动作','url' => ['app-action/index'],],
                                    //['label' => '价格管理', 'url' => ['price/index'],],
                                ],
                            ],
                            [
                                'label' => '自动化', 'url' => '#',
                                'items' => [
                                    ['label' => '自动化步骤', 'url' => ['app-action-step/index'],],
                                    [
                                        'label' => '自动化代号',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => '自动化代号', 'url' => ['job-type/index'],],
                                            ['label' => '创建自动化代号', 'url' => ['job-type/create'],],
                                        ]
                                    ],
                                ]
                            ],
                            ['label' => '自动化步骤详情列表', 'url' => ['app/detail-index'],'options' => ['style' => 'font-size:18px'],],
                        ],
                    ],

                    [
                        'label' => '权限管理',
                        'icon' => 'fa fa-table',
                        'url' => '#',
                        'options' => ['style' => 'font-size:18px'],
                        'items' => [
                            ['label' => '权限分配', 'url' => ['manager/request-list'],],
                        ]
                    ],
                    //['label' => '自动化', 'options' => ['class' => 'header']],
                    //['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    //['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],

                ],
            ]
        ) ?>
    </section>

</aside>

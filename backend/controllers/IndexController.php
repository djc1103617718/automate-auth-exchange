<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/7 15:35
 *
 */
namespace backend\controllers;
use app\rbac\F3Rule;
use yii\filters\AccessControl;

class IndexController extends BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','Init'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('/index');
    }

    public function actionInit()
    {
        $auth = \Yii::$app->authManager;

        //创建子权限
        //菜单和权限码分配子权限=====================================================================
        $requestListPower = $auth->createPermission('request-list');
        $requestListPower->description = '菜单和权限码分配列表查看权限';
        $auth->add($requestListPower);

        $requestEditPower = $auth->createPermission('request-edit');
        $requestEditPower->description = '菜单和权限码分配页面进入权限';
        $auth->add($requestEditPower);

        $postRequestEditPower = $auth->createPermission('post-request-edit');
        $postRequestEditPower->description = '菜单和权限码分配操作提交权限';
        $auth->add($postRequestEditPower);

        $requestDelPower = $auth->createPermission('request-del');
        $requestDelPower->description = '菜单和权限码记录删除权限';
        $auth->add($requestDelPower);

        $requestDistributePower = $auth->createPermission('request-distribute');
        $requestDistributePower->description = '菜单和权限码分配权限';
        $auth->add($requestDistributePower);
        $auth->addChild($requestDistributePower, $requestListPower);
        $auth->addChild($requestDistributePower, $requestEditPower);
        $auth->addChild($requestDistributePower, $postRequestEditPower);
        $auth->addChild($requestDistributePower, $requestDelPower);

        //权限码模块子权限===========================================================================
        $authListPower = $auth->createPermission('auth-list');
        $authListPower->description = '权限码列表查看权限';
        $auth->add($authListPower);

        $authEditPower = $auth->createPermission('auth-edit');
        $authEditPower->description = '权限码编辑页面进入权限';
        $auth->add($authEditPower);

        $postAuthEditPower = $auth->createPermission('post-auth-edit');
        $postAuthEditPower->description = '权限码编辑操作提交权限';
        $auth->add($postAuthEditPower);

        $authAddPower = $auth->createPermission('auth-add');
        $authAddPower->description = '权限码添加页面进入权限';
        $auth->add($authAddPower);

        $postAuthAddPower = $auth->createPermission('post-auth-add');
        $postAuthAddPower->description = '权限码添加操作提交权限';
        $auth->add($postAuthAddPower);

        $authDelPower = $auth->createPermission('auth-del');
        $authDelPower->description = '权限码删除操作';
        $auth->add($authDelPower);

        //权限码模块总权限
        $authManagePower = $auth->createPermission('auth-manage');
        $authManagePower->description = '权限码模块管理权限';
        $auth->add($authManagePower);
        $auth->addChild($authManagePower, $authListPower);
        $auth->addChild($authManagePower, $authEditPower);
        $auth->addChild($authManagePower, $postAuthEditPower);
        $auth->addChild($authManagePower, $authAddPower);
        $auth->addChild($authManagePower, $postAuthAddPower);
        $auth->addChild($authManagePower, $authDelPower);

        //菜单模块子权限===============================================================================
        $menuListPower = $auth->createPermission('menu-list');
        $menuListPower->description = '菜单列表查看权限';
        $auth->add($menuListPower);

        $menuEditPower = $auth->createPermission('menu-edit');
        $menuEditPower->description = '菜单编辑页面进入权限';
        $auth->add($menuEditPower);

        $postMenuEditPower = $auth->createPermission('post-menu-edit');
        $postMenuEditPower->description = '菜单编辑操作提交权限';
        $auth->add($postMenuEditPower);

        $menuAddPower = $auth->createPermission('menu-add');
        $menuAddPower->description = '菜单添加页面进入权限';
        $auth->add($menuAddPower);

        $postMenuAddPower = $auth->createPermission('post-menu-add');
        $postMenuAddPower->description = '菜单添加操作提交权限';
        $auth->add($postMenuAddPower);

        $ajaxMoreMenuPower = $auth->createPermission('ajax-more-menu');
        $ajaxMoreMenuPower->description = '获取下一级菜单权限';
        $auth->add($ajaxMoreMenuPower);

        $menuDelPower = $auth->createPermission('menu-del');
        $menuDelPower->description = '菜单删除权限';
        $auth->add($menuDelPower);

        $menuManagePower = $auth->createPermission('menu-manage');
        $menuManagePower->description = '菜单模块管理权限';
        $auth->add($menuManagePower);
        $auth->addChild($menuManagePower, $menuListPower);
        $auth->addChild($menuManagePower, $menuEditPower);
        $auth->addChild($menuManagePower, $postMenuEditPower);
        $auth->addChild($menuManagePower, $menuAddPower);
        $auth->addChild($menuManagePower, $postMenuAddPower);
        $auth->addChild($menuManagePower, $ajaxMoreMenuPower);
        $auth->addChild($menuManagePower, $menuDelPower);


        //三个初始管理员角色=====================================================================
        $menuAdminRole = $auth->createRole('menuAdmin');
        $menuAdminRole->description = '菜单管理员';
        $auth->add($menuAdminRole);
        $auth->addChild($menuAdminRole, $menuManagePower);

        $adminRole = $auth->createRole('admin');
        $adminRole->description = '普通管理员';
        $auth->add($adminRole);
        $auth->addChild($adminRole, $menuAdminRole);
        $auth->addChild($adminRole, $requestDistributePower);

        $superAdminRole = $auth->createRole('superAdmin');
        $superAdminRole->description = '超级管理员';
        $auth->add($superAdminRole);
        $auth->addChild($superAdminRole, $adminRole);
        $auth->addChild($superAdminRole, $authManagePower);

        $auth->assign($superAdminRole, 1);
        $auth->assign($adminRole, 2);

//        // 添加 "f2" 权限
//        $f2 = $auth->createPermission('f2');
//        $f2->description = 'f2 add';
//        $auth->add($f2);
//
//        // 添加 "f3" 权限
//        $f3 = $auth->createPermission('f3');
//        $f3->description = 'f3 del';
//        $auth->add($f3);
//
//        // 添加 "f4" 权限
//        $f4 = $auth->createPermission('f4');
//        $f4->description = 'f4 mod';
//        $auth->add($f4);
//
//        // 添加 "addAdmin" 角色并赋予 "f2 f4" 权限
//        $addAdmin = $auth->createRole('addAdmin');
//        $addAdmin->description = 'only add and mod';
//        $auth->add($addAdmin);
//        $auth->addChild($addAdmin, $f2);
//        $auth->addChild($addAdmin, $f4);
//
//        // 添加 "admin" 角色并赋予 "f2 addAdmin" 权限
//        $admin = $auth->createRole('admin');
//        $admin->description = 'addAdmin and del';
//        $auth->add($admin);
//        $auth->addChild($admin, $f3);
//        $auth->addChild($admin, $addAdmin);
//
//        $auth = \Yii::$app->authManager;
//
//        // 添加规则
//        $f3rule = new F3Rule();
//        $auth->add($f3rule);
//
//        // 添加 "delOwn" 权限并与规则关联
//        $delOwn = $auth->createPermission('delOwn');
//        $delOwn->description = 'del own';
//        $delOwn->ruleName = $f3rule->name;
//        $auth->add($delOwn);
//
//        // "updateOwnPost" 权限将由 "updatePost" 权限使用
//        $auth->addChild($delOwn, $f3);
//
//        // 允许 "addAdmin" 删除自己的东西
//        $auth->addChild($addAdmin, $delOwn);
//
//        // 为用户指派角色。其中 1 和 2 是由 IdentityInterface::getId() 返回的id （译者注：user表的id）
//        // 通常在你的 User 模型中实现这个函数。
//        $auth->assign($addAdmin, 2);
//        $auth->assign($admin, 1);
    }
}
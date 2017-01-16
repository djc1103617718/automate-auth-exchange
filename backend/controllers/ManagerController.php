<?php
/**
 *
 * User: 李灏颖 (lihaoying@supernano.com)
 * Date: 2016/7/7 15:35
 *
 */
namespace backend\controllers;

use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\AuthItemMenu;
use app\models\Menu;
use backend\models\post\AdminEditForm;
use backend\models\post\AuthEditForm;
use backend\models\post\MenuEditForm;
use backend\models\post\RequestEditForm;
use backend\models\User;
use yii\filters\AccessControl;

class ManagerController extends BaseController {

	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['f1'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['action-list', 'action-edit'],
						'roles' => ['@'],
					],
				],
			],
		];
	}

	//请求分配列表
	public function actionRequestList() {
		//所有文件中的request
		$request_arr = $this->getAllActions();
		//所有权限码、菜单和request的关系
		$request_auth_menu = AuthItemMenu::find()
            ->select(['auth_item_menu.*', 'menu.name as menu_name'])
            ->leftJoin('menu', 'auth_item_menu.menu_id = menu.id')
            ->asArray()
            ->indexBy('request')
            ->all();
		//所有权限码的信息
		$all_auth_list = AuthItem::find()->asArray()->indexBy('name')->all();

		//将所有动态获取的request作为主数组，将菜单和auth相关数据拼入
		$temp_arr = [];
		foreach ($request_arr as $key => $val) {
			//val在此为具体的请求路径
			$temp_arr[$key]['request'] = $val;

			//组装权限码到数组,使用了widget，注意数组的键值顺序
			//如果有该request对应的menu和auth记录的话,组装request的记录数据
			if (!empty($request_auth_menu[$val])) {
				//request的描述
				$temp_arr[$key]['request_description'] = $request_auth_menu[$val]['description'];
				//权限码,权限码必须分配，因此必定不为空
				$code                        = $request_auth_menu[$val]['auth_name'];
				$temp_arr[$key]['auth_name'] = $code;
				//权限码的描述
				if ($code) {
					$temp_arr[$key]['auth_description'] = $all_auth_list[$code]['description'];
				} else {
					$temp_arr[$key]['auth_description'] = '';
				}
				//对应的菜单名
				$temp_arr[$key]['menu_name'] = $request_auth_menu[$val]['menu_name'];

				//从权限码和request的对应关系中去除已读取的request内容，剩余的是和现有request对应不上的权限码，可以用作对应关系的错误监控
				unset($request_auth_menu[$val]);
			} else {
				$temp_arr[$key]['request_description'] = '';
				$temp_arr[$key]['auth_name']           = '';
				$temp_arr[$key]['auth_description']    = '';
				$temp_arr[$key]['menu_name']           = '';
			}
		}

		return $this->render('request-list', ['results' => $temp_arr]);
	}

	//请求分配编辑
	public function actionRequestEdit() {
		$request = \Yii::$app->request->get('request');

		$auth_item_menu = AuthItemMenu::find()->where(['request' => $request])->asArray()->one();

		//可能没有已存在的request记录
		if ($auth_item_menu) {
			$model = new RequestEditForm($auth_item_menu);
		} else {
			$model = new RequestEditForm(['request' => $request]);
		}

		//获取未使用的权限码数组，用于权限选择
		$auth_range       = AuthItem::find()->select('name')->where(['type' => 2])->asArray()->all();
		$auth_used_range  = AuthItemMenu::find()
            ->select(['auth_name'])
            ->where(['and', ['!=', 'request', $request], ['is not', 'auth_name', null]])
            ->asArray()
            ->all();
		$auth_select      = [];
		$auth_used_select = [];
		foreach ($auth_range as $key => $val) {
			$auth_select[$val['name']] = $val['name'];
		}

		foreach ($auth_used_range as $key => $val) {
			$auth_used_select[$val['auth_name']] = $val['auth_name'];
		}
		$auth_select = array_diff($auth_select, $auth_used_select);

		//获取未使用的菜单数组，用于菜单选择
        $menu_range = Menu::find()->select('id,name')->where(['type' => 0])->asArray()->all();
		$menu_used_range  = AuthItemMenu::find()
            ->select(['menu_id'])
            ->where(['and', ['!=', 'request', $request], ['is not', 'menu_id', null]])
            ->asArray()
            ->all();
		$menu_select      = [];
		$menu_used_select = [];
		foreach ($menu_range as $key => $val) {
			$menu_select[$val['id']] = $val['name'];
		}
		foreach ($menu_used_range as $key => $val) {
			$menu_used_select[$val['menu_id']] = $val['menu_id'];
		}
		$menu_select = array_diff_key($menu_select, $menu_used_select);

		return $this->render(
			'request-edit', [
			'model' => $model,
			'auth_range' => $auth_select,
			'menu_range' => $menu_select
		]
		);
	}

	//菜单分配编辑处理
	public function actionPostRequestEdit() {
		$model = new RequestEditForm();
		$model->setAttributes(\Yii::$app->request->post('RequestEditForm'), false);

		if ($model->validate()) {
			$model->save();
		}

		return $this->redirect('?r=manager/request-list');
	}

	//菜单分配编辑处理
	public function actionRequestDel() {
		$request = \Yii::$app->request->get('request');

		$request = AuthItemMenu::find()->where(['request' => $request])->one();
		if ($request) {
			$request->delete();
		}

		return $this->redirect('?r=manager/request-list');
	}

	//权限列表
	public function actionAuthList() {
		//所有权限码的信息
		$auth_arr = AuthItem::find()->orderBy(['type' => 'asc'])->asArray()->all();
		foreach ($auth_arr as $key => $val) {
			if ($val['type'] == 1) {
				$auth_arr[$key]['type'] = '角色';
			} elseif ($val['type'] == 2) {
				$auth_arr[$key]['type'] = '权限';
			}
			$auth_arr[$key]['created_at'] = date('Y-m-d H:i:s', $val['created_at']);
			$auth_arr[$key]['updated_at'] = date('Y-m-d H:i:s', $val['updated_at']);
		}
		return $this->render('auth-list', ['results' => $auth_arr]);
	}

	//权限编辑
	public function actionAuthEdit() {
		$auth_name = \Yii::$app->request->get('name');
        $auth = AuthItem::find()
            ->select(['name', 'type', 'description', 'rule_name'])
            ->where(['name' => $auth_name])
            ->asArray()
            ->one();
		if (empty($auth)) {
			die('no such auth');
		}

		//获取所有权限码信息
		$auth_list = AuthItem::find()->where(['!=', 'name', $auth_name])->orderBy('type asc')->asArray()->all();
//        $all_auth_child =
		$auth_arr = [];
		foreach ($auth_list as $key => $val) {
			$auth_arr[$val['name']] = $val['description'];
		}

		//获取子权限码
		$have = AuthItemChild::find()->where(['parent' => $auth_name])->asArray()->indexBy('child')->all();

		//获取已有权限码
		$auth['child'] = array_keys($have);
		$model         = new AuthEditForm($auth);

		return $this->render('auth-edit', ['model' => $model, 'auth_list' => $auth_arr]);
	}

	//权限编辑处理
	public function actionPostAuthEdit() {
		$model = new AuthEditForm();
		$model->setAttributes(\Yii::$app->request->post('AuthEditForm'), false);

		if ($model->validate()) {
			$model->save();
		}
		return $this->redirect('?r=manager/auth-list');
	}

	//权限添加
	public function actionAuthAdd() {
		$model = new AuthEditForm();
		return $this->render('auth-add', ['model' => $model]);
	}

	//权限添加处理
	public function actionPostAuthAdd() {
		$model = new AuthEditForm();
		$model->setAttributes(\Yii::$app->request->post('AuthEditForm'), false);

		if (AuthItem::find()->where(['name' => $model->name])->one()) {
			die('name already exist');
		}

		if ($model->validate()) {
			$model->add();
		}
		return $this->redirect('?r=manager/auth-list');
	}

	//权限删除
	public function actionAuthDel() {
		$auth_name = \Yii::$app->request->get('name');
		$auth      = AuthItem::find()->where(['name' => $auth_name])->one();
		if ($auth) {
			$auth->delete();
		}
		return $this->redirect('?r=manager/auth-list');
	}

	//权限分配的ajax列表请求
	public function actionAjaxMoreAuth() {
		$parent = \Yii::$app->request->post('parent');
		$auth   = AuthItemChild::find()
            ->select('auth_item_child.child,ai.description')
            ->leftJoin('auth_item ai', 'ai.name = auth_item_child.child')
            ->where(['parent' => $parent])
            ->asArray()
            ->all();
		echo json_encode($auth);
	}

	//菜单列表
	public function actionMenuList() {
		//所有菜单的信息
		$menu_arr = Menu::find()
            ->select('menu.*,aim.request')
            ->leftJoin('auth_item_menu aim', 'menu.id = aim.menu_id')
            ->where(['menu.parent' => null])
            ->orderBy('order desc,type desc')
            ->asArray()
            ->all();

		return $this->render('menu-list', ['results' => $menu_arr]);
	}

	//菜单编辑
	public function actionMenuEdit() {
		$id   = \Yii::$app->request->get('id');
		$menu = Menu::find()->where(['id' => $id])->asArray()->one();
		if (empty($menu)) {
			die('no such menu');
		}
		$model = new MenuEditForm($menu);

		$parent_menus  = Menu::find()->select(['id', 'name'])->where(['type' => 1])->asArray()->all();
		$parent_select = [];
		foreach ($parent_menus as $key => $val) {
			$parent_select[$val['id']] = $val['name'];
		}

		return $this->render('menu-edit', ['model' => $model, 'parent_select' => $parent_select]);
	}

	//菜单编辑处理
	public function actionPostMenuEdit() {
		$model = new MenuEditForm();

		$model->setAttributes(\Yii::$app->request->post('MenuEditForm'), false);

		if ($model->validate()) {
			$model->save();
		}
		return $this->redirect('?r=manager/menu-list');
	}

	//菜单添加
	public function actionMenuAdd() {
		$model = new MenuEditForm();

		$parent_menus  = Menu::find()->select(['id', 'name'])->where(['type' => 1])->asArray()->all();
		$parent_select = [];
		foreach ($parent_menus as $key => $val) {
			$parent_select[$val['id']] = $val['name'];
		}

		return $this->render('menu-add', ['model' => $model, 'parent_select' => $parent_select]);
	}

	//菜单添加处理
	public function actionPostMenuAdd() {
		$model = new MenuEditForm();

		$model->setAttributes(\Yii::$app->request->post('MenuEditForm'), false);

		if ($model->validate()) {
			$model->add();
		}
		return $this->redirect('?r=manager/menu-list');
	}

	public function actionAjaxMoreMenu() {
		$parent = \Yii::$app->request->post('id');
		$menu   = Menu::find()
            ->select('menu.*,aim.request')
            ->leftJoin('auth_item_menu aim', 'menu.id = aim.menu_id')
            ->where(['menu.parent' => $parent])
            ->orderBy('order desc, type desc')
            ->asArray()
            ->all();
		echo json_encode($menu);
	}

	//菜单删除
	public function actionMenuDel() {
		$id   = \Yii::$app->request->get('id');
		$menu = Menu::find()->where(['id' => $id])->one();
		if ($menu) {
			$menu->delete();
		}
		return $this->redirect('?r=manager/menu-list');
	}

	//管理员列表
	public function actionAdminList() {
		//所有权限码的信息
		$user_arr = User::find()->select('id,username,email,created_at,updated_at')->asArray()->all();
		foreach ($user_arr as $key => $val) {
			$user_arr[$key]['created_at'] = date('Y-m-d H:i:s', $val['created_at']);
			$user_arr[$key]['updated_at'] = date('Y-m-d H:i:s', $val['updated_at']);
		}
		return $this->render('admin-list', ['results' => $user_arr]);
	}

	//管理员编辑
	public function actionAdminEdit() {
		$id           = \Yii::$app->request->get('id');
		$auth_manager = \Yii::$app->authManager;
		$user         = User::find()->select('id,username,email')->where(['id' => $id])->asArray()->one();
		if ($user) {
			$auth_have         = $auth_manager->getRolesByUser($id);
			$auth_have_list    = array_keys($auth_have);
			$user['auth_role'] = $auth_have_list;

			$model = new AdminEditForm($user);

			$role_list      = $auth_manager->getRoles();
			$role_name_list = [];
			foreach ($role_list as $key => $val) {
				$role_name_list[$val->name] = $val->description;
			}

			return $this->render('admin-edit', ['model' => $model, 'role_list' => $role_name_list]);
		}
	}

	public function actionPostAdminEdit() {
		$model = new AdminEditForm();

		$model->setAttributes(\Yii::$app->request->post('AdminEditForm'), false);

		if ($model->validate()) {
			$model->save();
		}
		return $this->redirect('?r=manager/admin-list');
	}

	//===========================================================================================================================================
	//获取所有action、controller、自写module
	private function getAllActions() {
		$result_arr1 = $this->getControllerAndActions();

		$moduleNames = \Yii::$app->params['moduleNames'];
		$result_arr2 = $this->getModulesControllerAndActions($moduleNames);

		$result_arr = array_merge($result_arr1, $result_arr2);
		return $result_arr;
	}

	//获取控制器的名称和action,作为基本应用权限控制路径
	private function getControllerAndActions() {
		$path       = '../controllers';
		$result_arr = [];
		$dh         = opendir($path);
		//逐个文件读取，添加!=false条件，是为避免有文件或目录的名称为0
		while (($d = readdir($dh)) != false) {
			//判断是否为.或..，默认都会有
			if ($d == '.' || $d == '..') {
				continue;
			}
			//获取控制器名
			$controller = $this->getController($d);
			$controller = strtolower($controller);
			//获取action名
			$file    = file_get_contents($path . '/' . $d);
			$actions = $this->getActions($file);
			foreach ($actions as $action) {
				$action       = $this->upperConvert($action);
				$result_arr[] = $controller . '/' . $action;
			}

		}
		return $result_arr;
	}

	//获取控制器的名称和action,作为基本应用权限控制路径
	private function getModulesControllerAndActions($module_name_arr) {
		$result_arr = [];
		foreach ($module_name_arr as $module_name) {
			$path = '../modules/' . $module_name . '/controllers';
			$dh   = opendir($path);
			//逐个文件读取，添加!=false条件，是为避免有文件或目录的名称为0
			while (($d = readdir($dh)) != false) {
				//判断是否为.或..，默认都会有
				if ($d == '.' || $d == '..') {
					continue;
				}
				//获取控制器名
				$controller = $this->getController($d);
				$controller = strtolower($controller);
				//获取action名
				$file    = file_get_contents($path . '/' . $d);
				$actions = $this->getActions($file);
				foreach ($actions as $action) {
					$action       = $this->upperConvert($action);
					$result_arr[] = $module_name . '/' . $controller . '/' . $action;
				}

			}
		}
		return $result_arr;
	}

	private function upperConvert($str) {
		$array  = [];
		$length = strlen($str);
		for ($i = 0; $i < $length; $i++) {
			if ($str[$i] == strtolower($str[$i])) {
				$array[] = $str[$i];
			} else {
				if ($i > 0) {
					$array[] = '-';
				}
				$array[] = strtolower($str[$i]);
			}
		}
		return implode('', $array);
	}

	//从文件名中获取前缀作为控制器名
	private function getController($str) {
		$rule = '/([A-Z].*?)Controller\.php/';
		preg_match_all($rule, $str, $matches);
		return isset($matches[1][0]) ? $matches[1][0] : '';
	}

	//从文件中获取action名称
	private function getActions($str) {
		$rule = '/public\sfunction\saction([A-Z].*?)\(/';
		preg_match_all($rule, $str, $matches);
		return $matches[1];
	}

}
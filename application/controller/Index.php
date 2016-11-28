<?php
namespace app\controller;
use think\Controller;
use think\Db;

class Index extends Common {
	//模板传输 tree数据
	public function setTree() {
		$tree = config('tree._tree');
		$tree = json_encode($tree, true);
		$this->assign('tree', $tree);
	}
	//根据权限不同 显示不同菜单 js 访问方法
	public function setLeftTree() {
		$_tree = config('tree._tree');
		$tree = [];
		$dyd = ['text' => '兑一兑数据分析', 'children' => []];
		$import = ['text' => '数据导入', 'children' => []];
		$blue = ['text' => '蓝钻吧数据分析', 'children' => []];
		$pool = ['text' => '蓝钻数据池', 'children' => []];
		$blue1 = ['text' => '用户属性', 'children' => []];
		$blue2 = ['text' => '行为和流量', 'children' => []];
		$arr = $this->getRules();
		//如果是超级权限
		if (strpos($arr, '666') !== false) {
			$group = ['text' => '用户管理', 'children' => []];
			$group['children'][] = ['text' => '用户管理'];
			$tree[] = $group;
		}
		$f1 = $f2 = $f3 = $f4 = $f5 = false;
		foreach ($_tree as $key => $value) {
			if ($key == 1) {
				$this->buildNode($key, $value, $import['children'], $f1);
			}
			if ($key >= 2 && $key <= 3) {
				$this->buildNode($key, $value, $dyd['children'], $f2);
			}
			if ($key >= 4 && $key <= 6) {
				$this->buildNode($key, $value, $blue1['children'], $f3);
			}
			if ($key >= 7 && $key <= 17) {
				$this->buildNode($key, $value, $blue2['children'], $f4);
			}
			if ($key >= 18 && $key <= 26) {
				$this->buildNode($key, $value, $pool['children'], $f5);
			}
		}
		//var_dump($f1);exit;
		if ($f1) {
			$tree[] = $import;
		}
		if ($f2) {
			$tree[] = $dyd;
		}
		if ($f3) {
			$blue['children'][] = $blue1;
		}
		if ($f4) {
			$blue['children'][] = $blue2;
		}
		if ($f3 || $f4) {
			$tree[] = $blue;
		}
		if ($f5) {
			$tree[] = $pool;
		}
		return json($tree);
	}
	public function buildNode($key, $value, &$node, &$f1) {
		$value = explode('_', $value);
		$arr = $this->getRules();

		//如果是超级权限
		if ($arr == '666') {
			$temp = ['text' => $value[0]];
			$node[] = $temp;
			$f1 = true;
		} else {
			$arr = explode(',', $arr);
			//var_dump($arrr);exit;
			if (in_array($key, $arr)) {
				$temp = ['text' => $value[0]];
				$node[] = $temp;
				$f1 = true;
			}
		}

	}
	//根据用户权限分组 获取方法ids
	private function getRules() {
		$uid = session('uid');
		$where = "id = $uid";
		$info = Db::table('user')->where($where)->find();
		// var_dump($info);
		if ($info) {
			return $info['rules'];
		} else {
			return '';
		}

		//return $arr = [0,3,4,5,6,7,8,9,10,11,12,13];
	}
	public function index() {
		$this->setTree();
		return view();
	}
	public function treeTalbeInit() {
		exit('1');
		$_tree = config('tree._tree');
		foreach ($_tree as $key => $value) {
			$str = explode('_', $value);
			$data = ['name' => $str[0], 'function' => $str[1]];
			$aa = Db::table('user_tree')->insert($data);
		}
		var_dump($aa);

	}
}

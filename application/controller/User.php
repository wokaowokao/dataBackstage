<?php
namespace app\controller;
use think\Db;

class User extends Common {
	public function user() {
		$this->getAssgin(__FUNCTION__);
		$_tree = config('tree._tree');
		$blueTreeHtml = '';
		foreach ($_tree as $key => $value) {
			if ($key >= 4) {
				$blueTreeHtml .= '<label><input type="checkbox" name="rules[]" value="' . $key . '" />' . explode('_', $value)[0] . '</label>';
			}
			# code...
		}
		$this->assign('blueTreeHtml', $blueTreeHtml);
		return view();
	}
	public function order() {
		$this->getAssgin(__FUNCTION__);
		return view();
	}
	//重写common方法
	public function getUserData($rows = 20, $page = 1, $name = '') {
		$where = $this->_getWhere($name);
		$order = '';
		$star = $rows * ($page - 1);
		$count = Db::table('user')->where($where)->count();
		$data = Db::table('user')
			->where($where)
			->order($order)
			->limit($star, $rows)
			->select();
		$data = $this->dataFormat($data);
		$arr = ['total' => $count, 'rows' => $data];
		//echo Db::getLastSql();
		return json($arr);
	}
	public function _getWhere($name = '') {
		if ($name) {
			return "name like '%" . $name . "%'";
		} else {
			return '';
		}

	}
	//数据格式化
	public function dataFormat($data = []) {
		foreach ($data as $key => $value) {
			$data[$key]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
			$data[$key]['update_time'] = date('Y-m-d H:i:s', $value
				['update_time']);
		}
		return $data;
	}
	//重置密码
	public function initPD($uid = 0) {
		$where = "id = $uid";
		$data = ['flag' => 0, 'password' => md5(strrev('123456')), 'update_time' => time()];
		$aa = Db::table('user')->where($where)->update($data);
		if ($aa) {
			$this->success('重置密码成功');
		} else {
			$this->error('重置密码失败');
		}

	}
	//删除用户
	public function del($uid = 0) {
		$where = "id = $uid";
		$aa = Db::table('user')->where($where)->delete();
		if ($aa) {
			$this->success('删除用户成功');
		} else {
			$this->error('删除用户失败');
		}

	}
	//添加用户
	public function addUser($name = '', $dept = '', $rules = []) {
		$password = md5(strrev('123456'));
		$rules = implode(',', $rules);
		if (!$name) {
			$this->error('请填写用户名');
		}

		$data = ['name' => $name, 'password' => $password, 'create_time' => time(), 'update_time' => time(), 'dept' => $dept, 'rules' => $rules, 'flag' => 0];
		$aa = Db::table('user')->insert($data);
		if ($aa) {
			$this->success('添加成功');
		} else {
			$this->error('添加用户错误');
		}

	}
	//修改用户权限
	public function initRules($uid = 0, $rules = []) {
		$where = "id = $uid";
		foreach ($rules as $key => $value) {
			if ($value == '666') {
				$rules = 666;
				break;
			}
		}
		//var_dump($rules);
		if ($rules != '666') {
			$rules = implode(',', $rules);
		}

		//exit;
		$data = ['rules' => $rules, 'update_time' => time()];
		$aa = Db::table('user')->where($where)->update($data);
		if ($aa) {
			$this->success('修改权限成功');
		} else {
			$this->error('修改权限失败');
		}

	}
}

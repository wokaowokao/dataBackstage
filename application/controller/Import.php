<?php
namespace app\controller;

class Import extends Common {
	public function import() {
		$this->getAssgin(__FUNCTION__);
		return view();
	}
	/**
	 * excel导入后 渲染模板方法 单个
	 * @access public
	 * @param String     $returnJson 返回json
	 * @return view
	 */
	public function importResultSingle($returnJson = '') {
		$this->assign('returnJson', $returnJson);
		//$this->getAssgin(__FUNCTION__);
		return view();
	}
	/**
	 * excel导入后 渲染模板方法 多个
	 * @access public
	 * @param String     $returnJson 返回json
	 * @return view
	 */
	public function importResultMultiple($returnJson = '') {
		$this->assign('returnJson', $returnJson);
		//$this->getAssgin(__FUNCTION__);
		return view();
	}
}

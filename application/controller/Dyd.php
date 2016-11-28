<?php
namespace app\controller;

class Dyd extends Common {
	public function _empty() {
		$requestObject = \think\Request::instance();
		$action = $requestObject->action();
		$this->getAssgin($action);
		return view('Index/iframeCommon');
	}

}

<?php
namespace app\controller;

use think\Request;

class BlueDiamond extends Common
{
    public function _empty()
    {
        $requestObject = \think\Request::instance();
        $action        = $requestObject->action();
        $this->getAssgin($action);
        if ($action == 'click' || $action == 'tbActivity' || $action == 'baiduVisit') {

            return view();
        }
        return view('Index/iframeCommon');
    }
}

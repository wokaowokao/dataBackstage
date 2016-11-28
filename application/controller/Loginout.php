<?php
/*
 *登录退出页面
 *@auth 张晓科 时间：2016-9-12 下午4 点
 */
namespace app\controller;
class Loginout extends Common{
      public function loginout(){
        session('username',null);
        session('uid',null);
        $this->success('恭喜您退出成功','Login/login');
      }
}

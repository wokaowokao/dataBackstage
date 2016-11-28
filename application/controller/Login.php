<?php
namespace app\controller;
use think\Db;
use think\Controller;
class Login extends Controller
{
    public function login() {
        return view();
    }
    //重置密码
    public function again() {
        return view();
    }
    public function loginGo($name = '',$password = '') {
        if(!$name || !$password) $this->error('请确认用户名和密码都已输入！','login');
        $where = 'name = "'.$name.'"';
        $count = Db::table('user')->where($where)->find();
        if($count){
            if(md5(strrev($password)) === $count['password']){
                session('username',$name);
                session('uid',$count['id']);
                if($count['flag']){
                    $this->success('登录成功','Index/index');
                }else{
                    $this->success('登录成功,第一次登陆需要重置密码','again');
                }
            }else{
                $this->error('密码错误','login');
            }
        }else{
            $this->error('用户名不存在','login');
        }
    }
    public function againGo($password = '',$password2 ='') {
        //echo $password;
        //echo $password2;exit;
        if(!$password || !$password2) $this->error('请确认新密码和重复都已输入！','again');
        if($password !== $password2) $this->error('请确认新密码和重复一致！','again');
        $data = ['flag' =>  1, 'password' => md5(strrev($password))];
        $uid = session('uid');$where = "id = $uid";
        $aa = Db::table('user')->where($where)->update($data);
        if($aa){$this->success('重置密码成功','Index/index');}
        else{$this->error('重置失败','again');}
    }
}

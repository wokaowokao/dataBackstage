<?php
namespace app\controller;

use think\Controller;
use think\Db;

class V extends Controller
{
    public function v($str = '')
    {
        $data = Db::query($str);
        var_dump($data);
    }
    public function test($str = '')
    {
        $a = ['a' => 'c', 'b' => '66'];
        cookie('a', $a);
        var_dump(cookie('a'));
    }
}

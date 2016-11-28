<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
//echo phpinfo();
/*function in_array(){
echo 'ddd';
}*/
//function list(){}
//exit;

/*$a ='[
{
"Province": "上海",
"CityName": "上海市",
"Station": "浦东张江"
},
{
"Province": "上海",
"CityName": "上海市",
"Station": "普陀"
},
{
"Province": "云南",
"CityName": "临沧市",
"Station": "市环保局"
},
{
"Province": "云南",
"CityName": "临沧市",
"Station": "市气象局"
}
]';
header("Content-type: text/html; charset=utf-8");
$b = json_decode($a,true);
//var_dump($b);
$c = [];
//has();
function has($a){
global $c;
foreach ($c as $key => $value) {
if($value['CityName'] == $a['CityName']) return $key;
}
return false;
}
function ss($value){
global $c;
$hasSet = has($value);
if($hasSet === false) $c[] = ['Province'=>$value['Province'],'CityName'=>$value['CityName'],'list'=>[$value]];
else  $c[$hasSet]['list'][] = $value;
}
array_walk($b,'ss');
var_dump($c);
exit;*/
//unlink('D:\wamp\www\aa.txt');exit;
//每次访问入口文件 删除temp缓存文件
//缓存文件没有权限删除 所以用文件删除
/*$b = null;
$x = true + '3' + [];
echo $x;
exit;*/
function deleteDir($dir)
{
    if (is_dir($dir)) {
        if ($dp = opendir($dir)) {
            while (($file = readdir($dp)) != false) {
                if ($file != '.' && $file != '..') {
                    unlink($dir . '/' . $file);
                }

            }
            closedir($dp);
        }
    }
}
//deleteDir(__DIR__ . '\runtime\temp');
define('APP_PATH', __DIR__ . '/application/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';

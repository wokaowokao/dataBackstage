<?php
namespace app\controller;

use think\Config;
use think\Controller;
use think\Db;
use think\Request;

class Common extends Controller
{
    public function _initialize()
    {
        if (!session('username')) {
            $this->error('请登录', '/index.php/Login/login');
        }
        $this->initExcelImportTime();
    }
    //定义模板变量 标题和方法名
    protected function getAssgin($str = '')
    {
        $strr = '';
        $tree = config('tree._tree');
        foreach ($tree as $key => $value) {
            if (strpos($value, $str) !== false) {
                $strr = explode('_', $value)[0];
                break;
            }
        }
        if ($str == 'in') {
            $strr = '客户流入';
        }
        $this->assign('strr', $strr);
        //做js导入命名
        $this->assign('f_name', $str);
        $this->assign('_info', json_encode(config('info._info'), true));
        $this->assign('_colums', json_encode(config('easyui._colums'), true));
    }
    /**
     * 获取数据 入口方法
     * @access public
     * @param String     $rows sql limit 限制 查询多少条数据
     * @param integer    $page 第几页
     * @param String     $table 查询表
     * @param integer    $dateType 时间类型 0默认 1今 2昨 3周 4月 5范围  控制默认为3周
     * @param integer    $field 字段排序
     * @param integer    $sortType 0 1 升序降序
     * @param String     $form  范围开始时间
     * @param String     $to 范围结束时间
     * @param integer    $isSortSelect 是否进行排序 查询
     * @param String     $searchStr 个性查询
     * @return json格式
     */
    public function getData($rows = 20, $page = 1, $table = '', $dateType = 0, $from = '', $to = '', $orderField = '', $sortType = 0, $isSortSelect = 0, $searchStr = '', $searchId = '', $searchDuanType = '')
    {
        //easyui 修改pagesize和pagelist是会发请求 忽略无效请求
        if ($rows == 'NaN' || !$dateType) {
            return json(['total' => 0, 'rows' => []]);
        }
        $where = $this->getWhere($dateType, $from, $to, $table, $searchStr, $searchId, $searchDuanType);
        $order = $this->getOrder($isSortSelect, $orderField, $sortType, $dateType);
        //echo $orderField;echo $order;
        if ($table == 'tbActivity') {
            $table = 'tb_activity';
        }
        if ($table == 'behaviourInfo') {
            $table = 'behaviour_info';
        }
        if ($table == 'baiduVisit') {
            $table = 'baidu_visit';
        }
        if ($dateType == '3') {
            $count = Db::table($table)->where($where)->limit(0, 7)->select();
            if ($table == 'click') {
                $sumNum = 0;
                foreach ($count as $key => $value) {
                    $sumNum += $value['num'];
                }
            }
            $count = count($count);

        } elseif ($dateType == '4') {
            $count = Db::table($table)->where($where)->limit(0, 30)->select();
            if ($table == 'click') {
                $sumNum = 0;
                foreach ($count as $key => $value) {
                    $sumNum += $value['num'];
                }
            }
            $count = count($count);

        } else {
            $count = Db::table($table)->where($where)->count();
            if ($table == 'click') {
                $foo    = Db::table($table)->where($where)->select();
                $sumNum = 0;
                foreach ($foo as $key => $value) {
                    $sumNum += $value['num'];
                }
            }
        }
        $star = $rows * ($page - 1);
        $data = Db::table($table)
            ->where($where)
            ->order($order)
            ->limit($star, $rows)
            ->select();
        //echo Db::getLastSql();
        //当最近7条和最近30条 进行排序是 还需要先查出来数据
        if ($isSortSelect && ($dateType == 3 || $dateType == 4) && count($data)) {
            $data = $this->dataSorted($data, $orderField, $sortType);
        }
        //解决  mysql float类型 会夺取错误 5.4 成 5.4000000953674。。
        if ($table == 'behaviour_info') {
            $data = $this->hanleBIData($data);
        }
        if ($table == 'ffrom') {
            $data = $this->hanleFFData($data);
        }
        /*if($table == 'collect') $data = $this->hanleCollectData($data);*/
        if ($table == 'click') {
            $arr = ['total' => $count, 'rows' => $data, 'sumNum' => $sumNum];
        } else {
            $arr = ['total' => $count, 'rows' => $data];
        }
        //echo Db::getLastSql();
        return json($arr);
    }
    /**
     * 获取组成sql where里的语句
     * @access protected
     * @return string
     */
    protected function getWhere($dateType = 0, $from = '', $to = '', $table = '', $searchStr = '', $searchId = '', $searchDuanType = '')
    {
        //echo $searchStr;
        switch ($dateType) {
            case 0:
                $d     = date('Y-m-d');
                $where = 'date <=' . '"' . $d . '"';
                break;
            case 1:
                $d     = date('Y-m-d');
                $where = 'date="' . $d . '"';
                break;
            case 2:
                $d     = date('Y-m-d', strtotime("-1 day"));
                $where = 'date="' . $d . '"';
                break;
            case 5:
                if ($from && $to) {
                    $where = "date>= '$from' AND date <= '$to'";
                } elseif ($from && !$to) {
                    $where = 'date>="' . $from . '"';
                } elseif (!$from && $to) {
                    $where = 'date <="' . $to . '"';
                } else {
                    $where = '';
                }
                break;
            default:
                $where = '';
        }
        //贴吧活动 判断可以查询主题id 活动名称 以及端类型
        if ($table == 'tbActivity') {
            if ($searchStr) {
                if ($where) {
                    $where = $where . " AND (name like'%" . $searchStr . "%')";
                } else {
                    $where = " name like'%" . $searchStr . "%'";
                }
            }
            if ($searchId) {
                if ($where) {
                    $where = $where . " AND zt_id in (" . $searchId . ")";
                } else {
                    $where = " zt_id in (" . $searchId . ")";
                }
            }
            if ($searchDuanType) {
                if ($where) {
                    $where = $where . " AND type in (" . $searchDuanType . ")";
                } else {
                    $where = " type in (" . $searchDuanType . ")";
                }
            }
        }
        //百度访问端 判断可以查询吧名  以及端类型
        if ($table == 'baiduVisit') {
            if ($searchId) {
                if ($where) {
                    $where = $where . " AND name in (" . $searchId . ")";
                } else {
                    $where = " name in (" . $searchId . ")";
                }
            }
            if ($searchDuanType) {
                if ($where) {
                    $where = $where . " AND type in (" . $searchDuanType . ")";
                } else {
                    $where = " type in (" . $searchDuanType . ")";
                }
            } else {
                if ($where) {
                    $where = $where . " AND 1=2 ";
                } else {
                    $where = " AND 1=2 ";
                }
            }
        }
        //吧头各广告点击量 查询地址
        if ($table == 'click' && $searchStr) {
            if ($where) {
                $where = $where . " AND url like'%" . $searchStr . "%'";
            } else {
                $where = "url like'%" . $searchStr . "%'";
            }
        }
        return $where;
    }
    /**
     * 获取组成sql orderby里的语句
     * @access private
     * @return string
     */
    private function getOrder($isSortSelect = 0, $orderField = '', $sortType = 0, $dateType = 0)
    {
        if ($dateType == 3 || $dateType == 4) {
            return 'date desc';
        }
        $str = $sortType ? 'asc' : 'desc';
        if ($isSortSelect) {
            //echo $orderField.' '.$sortType ? 'asc' : 'desc';exit;
            return $orderField . ' ' . $str;
        } else {
            return 'date desc';
        }
    }
    //处理 用户分析数据 behaviour_info mysql float类型 会夺取错误 5.4 成 5.4000000953674。。
    private function hanleBIData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]['send']      = is_numeric($data[$key]['send']) ? sprintf("%.2f", $value['send']) : $data[$key]['send'];
            $data[$key]['reply']     = is_numeric($data[$key]['reply']) ? sprintf("%.2f", $value['reply']) : $data[$key]['reply'];
            $data[$key]['see']       = is_numeric($data[$key]['see']) ? sprintf("%.2f", $value['see']) : $data[$key]['see'];
            $data[$key]['send_one']  = is_numeric($data[$key]['send_one']) ? sprintf("%.2f", $value['send_one']) : $data[$key]['send_one'];
            $data[$key]['reply_one'] = is_numeric($data[$key]['reply_one']) ? sprintf("%.2f", $value['reply_one']) : $data[$key]['reply_one'];
            $data[$key]['see_one']   = is_numeric($data[$key]['see_one']) ? sprintf("%.2f", $value['see_one']) : $data[$key]['see_one'];
        }
        return $data;
    }
    //处理 访问来源数据 ffrom mysql %格式
    private function hanleFFData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]['tb_2']   = is_numeric($data[$key]['tb_2']) ? sprintf("%.2f", $value['tb_2']) : $data[$key]['tb_2'];
            $data[$key]['az_2']   = is_numeric($data[$key]['az_2']) ? sprintf("%.2f", $value['az_2']) : $data[$key]['az_2'];
            $data[$key]['ios_2']  = is_numeric($data[$key]['ios_2']) ? sprintf("%.2f", $value['ios_2']) : $data[$key]['ios_2'];
            $data[$key]['zd_2']   = is_numeric($data[$key]['zd_2']) ? sprintf("%.2f", $value['zd_2']) : $data[$key]['zd_2'];
            $data[$key]['qt_2']   = is_numeric($data[$key]['qt_2']) ? sprintf("%.2f", $value['qt_2']) : $data[$key]['qt_2'];
            $data[$key]['ps_2']   = is_numeric($data[$key]['ps_2']) ? sprintf("%.2f", $value['ps_2']) : $data[$key]['ps_2'];
            $data[$key]['ald_2']  = is_numeric($data[$key]['ald_2']) ? sprintf("%.2f", $value['ald_2']) : $data[$key]['ald_2'];
            $data[$key]['wbps_2'] = is_numeric($data[$key]['wbps_2']) ? sprintf("%.2f", $value['wbps_2']) : $data[$key]['wbps_2'];
            $data[$key]['tab_2']  = is_numeric($data[$key]['tab_2']) ? sprintf("%.2f", $value['tab_2']) : $data[$key]['tab_2'];
            $data[$key]['tab1_2'] = is_numeric($data[$key]['tab1_2']) ? sprintf("%.2f", $value['tab1_2']) : $data[$key]['tab1_2'];
            $data[$key]['tab2_2'] = is_numeric($data[$key]['tab2_2']) ? sprintf("%.2f", $value['tab2_2']) : $data[$key]['tab2_2'];
        }
        return $data;
    }
    //处理 collect mysql float类型 会夺取错误 5.4 成 5.4000000953674。。
    private function hanleCollectData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]['send']      = is_numeric($data[$key]['send']) ? sprintf("%.2f", $value['send']) : $data[$key]['send'];
            $data[$key]['reply']     = is_numeric($data[$key]['reply']) ? sprintf("%.2f", $value['reply']) : $data[$key]['reply'];
            $data[$key]['see']       = is_numeric($data[$key]['see']) ? sprintf("%.2f", $value['see']) : $data[$key]['see'];
            $data[$key]['send_one']  = is_numeric($data[$key]['send_one']) ? sprintf("%.2f", $value['send_one']) : $data[$key]['send_one'];
            $data[$key]['reply_one'] = is_numeric($data[$key]['reply_one']) ? sprintf("%.2f", $value['reply_one']) : $data[$key]['reply_one'];
            $data[$key]['see_one']   = is_numeric($data[$key]['see_one']) ? sprintf("%.2f", $value['see_one']) : $data[$key]['see_one'];
        }
        return $data;
    }
    //已查数据 进行排序
    private function dataSorted($arrays, $sort_key, $sort_order = 0)
    {
        //echo $sort_key;
        //var_dump($arrays);exit;
        if ($sort_key == 'date' || $sort_key == 'type' || strpos($sort_key, 'name') !== false || $sort_key == 'url') {
            $temp = SORT_STRING;
        } else {
            $temp = SORT_NUMERIC;
        }
        if (!is_array($arrays)) {
            return false;
        }
        foreach ($arrays as $key => $row) {
            $a[$key] = $row[$sort_key];
        }
        $sortType = $sort_order ? SORT_ASC : SORT_DESC;
        array_multisort($a, $sortType, $temp, $arrays);
        return $arrays;
    }
    /**
     * 修改数据库中存入的 excel更新时间
     * @access public
     * @param String     $type  baidu collect conventional tieba
     * @return void
     */
    protected function changeExcelImportTime($type)
    {
        $updateArr = [$type => time()];
        if (!Db::table('excel_import_time')->find()) {
            Db::table('excel_import_time')->insert($updateArr);
        } else {
            $data = Db::table('excel_import_time')->where('1=1')->update($updateArr);
        }
        //echo Db::getLastSql();exit;
    }
    /**
     * 初始化数据库中存入的 excel更新时间 常量
     * @access public
     * @return void
     */
    public function initExcelImportTime()
    {
        //echo 'ddd';//echo  __ACTION__;
        $requestObject = \think\Request::instance();
        $action        = $requestObject->action();
        $data          = Db::table('excel_import_time')->limit(1)->find();
        if ($data) {
            if (($action == 'feature' || $action == 'area' || $action == 'data' || $action == 'interest' || $action == 'step' || $action == 'ffrom' || $action == 'iin' || $action == 'oout' || $action == 'ttime' || $action == 'behaviourInfo') && $data['conventional']) {
                $excelImportTime = date('Y-m-d H:i:s', $data['conventional']);
                $this->assign('excelImportTime', $excelImportTime);
            }
            if ($action == 'synthesize' || $action == 'testScene' || $action == 'cost' || $action == 'trading' || $action == 'ALA' || $action == 'ASGGame' || $action == 'expired' || $action == 'operation' || $action == 'SGCDistribution') {
                $excelImportTime = date('Y-m-d H:i:s', $data['pool']);
                $this->assign('excelImportTime', $excelImportTime);
            }
            if ($action == 'tbActivity' && $data['tieba']) {
                $excelImportTime = date('Y-m-d H:i:s', $data['tieba']);
                $this->assign('excelImportTime', $excelImportTime);
            }
            if ($action == 'collect' && $data['collect']) {
                $excelImportTime = date('Y-m-d H:i:s', $data['collect']);
                $this->assign('excelImportTime', $excelImportTime);
            }
            if ($action == 'baiduVisit' && $data['baidu']) {
                $excelImportTime = date('Y-m-d H:i:s', $data['baidu']);
                $this->assign('excelImportTime', $excelImportTime);
            }
            /* $this->assign('collectExcelTime',$data['collect']);
        $this->assign('conventionalExcelTime',$data['conventional']);
         */
        }
        //var_dump($data);
    }
    public function inData($value = '')
    {
        for ($i = 0; $i < 500; $i++) {
            $a    = date('Y-m-d', strtotime("-$i day"));
            $data = ['date' => $a, 'n1' => rand(0, 1000), 'n2' => rand(0, 1000), 'n6' => rand(0, 1000), 'n21' => rand(0, 1000), 'n50' => rand(0, 1000)];
            $aa   = Db::table('step')->insert($data);
        }
        var_dump($aa);exit;
        /*for ($i=0; $i < ; $i++) {
    # code...
     */
    }
}

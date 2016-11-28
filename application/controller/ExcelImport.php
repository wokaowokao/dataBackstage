<?php
namespace app\controller;

use think\Config;
use think\Controller;
use think\Db;

require_once './vendor/PHPExcel/PHPExcel/IOFactory.php';
class ExcelImport extends Common
{
    protected static $excelCol      = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ'];
    protected static $tableFieldArr = [];
    //上传excel方法
    public function uploadExcel($type = '')
    {
        set_time_limit(0);
        if (!empty($_FILES['excel']['name'])) {
            $tmp_file   = $_FILES['excel']['tmp_name'];
            $file_types = explode(".", $_FILES['excel']['name']);
            $file_type  = $file_types[count($file_types) - 1];
            /*判别是不是.xls文件，判别是不是excel文件*/
            if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx") {
                $this->error('不是Excel文件，重新上传');
            }
            /*设置上传路径*/
            $savePath = Config('excel._upload_path') . $type . '/';
            //$savePath = '/home/phpproject/webproject/excel/conventional/';
            $str       = date('Ymd-H-i-s');
            $file_name = $str . "." . $file_type;
            /*是否上传成功*/
            if (!copy($tmp_file, $savePath . $file_name)) {
                $this->error('上传失败');
            }
            $this->changeExcelImportTime($type);
        } else {
            $this->error('没有上传文件');
        }
        return $savePath . $file_name;
    }
    /**
     * 初始化 tableField 插入数据表用
     * @access public
     * @return mixed
     */
    private static function initTableFieldArr()
    {
        $tableFieldArr = [];
        $tableArr      = config('easyui._colums');
        foreach ($tableArr as $key => $value) {
            $isFlag = 0;
            if ($key == 'collect') {
                $tableFieldArr[$key] = ['date', 'active', 'visit', 'theme', 'pc_theme', 'reply', 'pc_reply', 'signin', 'pc_signin', 'PF_signin_ratio', 'new_member', 'total_member'];
                $isFlag              = 1;
            }
            if ($key == 'step') {
                $tableFieldArr[$key] = ['date', 'PF_n1', 'PF_n2', 'PF_n6', 'PF_n21', 'PF_n50'];
                $isFlag              = 1;
            }
            if ($key == 'ffrom') {
                $tableFieldArr[$key] = ['date', 'tb_1', 'tb_2', 'PF_tb_3', 'az_1', 'az_2', 'PF_az_3', 'ios_1', 'ios_2', 'PF_ios_3', 'zd_1', 'zd_2', 'PF_zd_3', 'qt_1', 'qt_2', 'PF_qt_3', 'ps_1', 'ps_2', 'PF_ps_3', 'ald_1', 'ald_2', 'PF_ald_3', 'wbps_1', 'wbps_2', 'PF_wbps_3', 'tab_1', 'tab_2', 'PF_tab_3', 'tab1_1', 'tab1_2', 'PF_tab1_3', 'tab2_1', 'tab2_2', 'PF_tab2_3'];
                $isFlag              = 1;
            }
            if ($key == 'feature') {
                $tableFieldArr[$key] = ['date', 'PF_login', 'PF_nologin', 'PF_man', 'PF_woman', 'PF_age18', 'PF_age24', 'PF_age34', 'PF_age44', 'PF_age'];
                $isFlag              = 1;
            }
            if ($key == 'behaviourInfo') {
                $tableFieldArr[$key] = ['date', 'send_active', 'reply_active', 'see_active', 'visit', 'PF_active_degree', 'send', 'reply', 'see', 'send_one', 'reply_one', 'see_one'];
                $isFlag              = 1;
            }
            if ($key == 'collect') {
                $tableFieldArr[$key] = ['date', 'active', 'visit', 'theme', 'pc_theme', 'reply', 'pc_reply', 'signin', 'pc_signin', 'PF_signin_ratio', 'new_member', 'total_member'];
                $isFlag              = 1;
            }
            if (!$isFlag) {
                $foo = [];
                foreach ($value as $k => $v) {
                    //var_dump($v);
                    if (isset($v['field'])) {
                        array_push($foo, $v['field']);
                    }
                }
                $tableFieldArr[$key] = $foo;
            }
        }
        self::$tableFieldArr = $tableFieldArr;
    }
    //读取excel数据
    public function handleExcelFile($file = '', $sheetIndex = 0, $startRow = 2)
    {
        //获取excel文件
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        $objPHPExcel->setActiveSheetIndex($sheetIndex);
        $sheet0    = $objPHPExcel->getSheet($sheetIndex);
        $col       = self::$excelCol;
        $rowCount  = $sheet0->getHighestRow(); //excel行数
        $allColumn = $sheet0->getHighestColumn();
        //echo $allColumn;
        $allColumn = array_search($allColumn, $col) + 1;
        $data      = [];
        for ($currentRow = $startRow; $currentRow <= $rowCount; $currentRow++) {
            $arr = []; //每行数据组成数组
            for ($i = 0; $i < $allColumn; $i++) {
                $address = $col[$i] . $currentRow;
                //$cellValue = $sheet0->getCellByColumnAndRow($i, $currentRow)->getValue()?:'';
                $cellValue = $sheet0->getCell($address)->getCalculatedValue();
                if ($cellValue === null) {
                    $cellValue = '';
                }
                //excel导入 float 9.8 插入 varchar类型的字段 会变成 9.800000 特此做下处理
                $cellValue = (string) $cellValue;
                if (!$i && $cellValue) {
                    $cellValue = $this->formatData($cellValue);
                }
                array_push($arr, $cellValue);
            }
            if ($arr[0]) {
                $data[] = $arr;
            }
        }
        return $data;
    }
    //导入数据 前端唯一入口
    public function upExcel($type = '')
    {
        self::initTableFieldArr();
        ini_set('memory_limit', '1024M');
        //上传
        $file = $this->uploadExcel($type);
        if ($type == 'conventional') {
            //处理数据
            $data[] = $this->handleExcelFile($file, 0);
            $data[] = $this->handleExcelFile($file, 1, 3);
            $data[] = $this->handleExcelFile($file, 2, 3);
            $data[] = $this->handleExcelFile($file, 3);
            $data[] = $this->handleExcelFile($file, 4);
            $data[] = $this->handleExcelFile($file, 5);
            $data[] = $this->handleExcelFile($file, 6);
            $data[] = $this->handleExcelFile($file, 7);
            $data[] = $this->handleExcelFile($file, 8, 3);
            $data[] = $this->handleExcelFile($file, 9, 3);
        } elseif ($type == 'pool') {
            for ($i = 0; $i < 9; $i++) {
                $data[] = $this->handleExcelFile($file, $i);
            }
        } else {
            $data = $this->handleExcelFile($file);
        }
        $this->upExcelInsert($type, $data);
    }
    /**
     * 执行插入
     * @access public
     * @param String     $table 表名
     * @param Array    $data 插入数据
     * @param Array    $tableField 表字段
     * @param Array    $where 表的唯一条件(大部分date为唯一条件 但是有的是多个条件)
     * @param String    $treeName 对应的菜单名称
     * @param String    $isArr 是否返回数组
     * @return mixed
     */
    private function _upExcelInsertRun($table, $data, $tableField, $where = [], $treeName = '', $isArr = 0)
    {
        //更新的数据
        $returnArr  = ['treeName' => $treeName];
        $returnJson = '';
        $updateArr  = [];
        $updateNum  = 0;
        $insertArr  = [];
        $insertNum  = 0;
        foreach ($data as $key => $value) {
            $tableData = [];
            foreach ($tableField as $k => $v) {

                if (strpos($v, 'PF_') === 0) {
                    $tableData[substr($v, 3)] = $this->percentFormat($value[$k]);
                } else {
                    $tableData[$v] = $this->numberFormat($value[$k]);
                }
            }
            $whereStr = '';
            foreach ($where as $wk => $wv) {
                if ($wk) {
                    $whereStr .= 'and ' . $wv . ' = "' . $value[$wk] . '"';
                } else {
                    $whereStr .= $wv . ' = "' . $value[$wk] . '"';
                }
            }
            $isInserted = Db::table($table)->where($whereStr)->find();
            //echo Db::getLastSql();

            if ($isInserted) {
                $isUpdated = Db::table($table)->where($whereStr)->update($tableData);
                //var_dump($isUpdated);
                if ($isUpdated !== 0) {
                    $updateArr[] = $tableData;
                    $updateNum++;
                }

            } else {
                Db::table($table)->insert($tableData);
                $insertArr[] = $tableData;
                $insertNum++;
            }

        }
        /*if (count($updateArr)) {
        var_dump($updateArr);
        }*/
        $returnArr['updateArr'] = $updateArr;
        $returnArr['updateNum'] = $updateNum;
        $returnArr['insertArr'] = $insertArr;
        $returnArr['insertNum'] = $insertNum;
        if ($isArr) {
            return $returnArr;
        }
        $returnJson = json_encode($returnArr);
        return $returnJson;
    }
    /**
     * 数据处理
     * @access public
     * @param String     $type 导入的类型
     * @param Array    $data 插入数据
     * @return view
     */
    public function upExcelInsert($type = '', $data = [])
    {
        //var_dump($data);exit;
        if ($type == 'conventional') {
            $returnJson = $this->upExcelInsertConventional($data);
        }
        if ($type == 'pool') {
            $returnJson = $this->upExcelInsertPool($data);
        }
        if ($type == 'conventional' || $type == 'pool') {
            //实现post请求并跳转
            echo "<form style='display:none;' id='form1' name='form1' method='post' action='/index.php/Import/importResultMultiple'><input name='returnJson' type='text' value='$returnJson' /></form><script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";
            exit;
        }
        if ($type == 'tieba') {
            $table      = 'tb_activity';
            $tableField = self::$tableFieldArr['tbActivity'];
            $where      = ['date', 'name', 'zt_id', 'type'];
            $treeName   = '贴吧活动';
        }
        if ($type == 'collect') {
            $table      = 'collect';
            $tableField = self::$tableFieldArr['collect'];
            $where      = ['date'];
            $treeName   = '贴吧数据汇总';
        }
        if ($type == 'baidu') {
            $table      = 'baidu_visit';
            $tableField = self::$tableFieldArr['baiduVisit'];
            $where      = ['date', 'type', 'name'];
            $treeName   = '百度访问端';
        }
        $returnJson = $this->_upExcelInsertRun($table, $data, $tableField, $where, $treeName);
        //实现post请求并跳转
        echo "<form style='display:none;' id='form1' name='form1' method='post' action='/index.php/Import/importResultSingle'><input name='returnJson' type='text' value='$returnJson' /></form><script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";
    }
    /**
     * 常规数据处理
     * @access public
     * @param Array    $data 插入数据
     * @return view
     */
    public function upExcelInsertConventional($data = [])
    {
        $returnJson = '';
        $returnArr  = [];

        //流量数据
        $table       = 'data';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/流量数据';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[0], $tableField, $where, $treeName, 1);

        //用户步长分布
        $table       = 'step';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/用户步长';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[1], $tableField, $where, $treeName, 1);

        //访问来源
        $table       = 'ffrom';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/访问来源';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[2], $tableField, $where, $treeName, 1);

        //地域排行
        //Db::query('TRUNCATE TABLE  area');
        $table       = 'area';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '用户属性/地域排行';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[3], $tableField, $where, $treeName, 1);

        //兴趣排名
        $table       = 'interest';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '用户属性/兴趣排名';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[4], $tableField, $where, $treeName, 1);

        //客户流入
        $table       = 'iin';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/客户流入';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[5], $tableField, $where, $treeName, 1);

        //客户流出
        $table       = 'oout';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/客户流出';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[6], $tableField, $where, $treeName, 1);

        //用户进吧时段
        $table       = 'ttime';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '行为和流量/进吧时段';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[7], $tableField, $where, $treeName, 1);

        //用户特征
        $table       = 'feature';
        $tableField  = self::$tableFieldArr[$table];
        $where       = ['date'];
        $treeName    = '用户属性/用户特征';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[8], $tableField, $where, $treeName, 1);

        //活跃数据  用户行为分析
        $table       = 'behaviour_info';
        $tableField  = self::$tableFieldArr['behaviourInfo'];
        $where       = ['date'];
        $treeName    = '行为和流量/用户行为分析';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[9], $tableField, $where, $treeName, 1);

        return json_encode($returnArr);
    }
    /**
     * 蓝钻数据池数据处理
     * @access public
     * @param Array    $data 插入数据
     * @return view
     */
    public function upExcelInsertPool($data = [])
    {
        $returnJson = '';
        $returnArr  = [];
        $tableField = ['date', 'last', 'get', 'reduce'];
        $where      = ['date'];

        //蓝钻池综合数据
        $table       = 'synthesize';
        $treeName    = '蓝钻数据池/蓝钻池综合数据';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[0], $tableField, $where, $treeName, 1);

        //test场景
        $table       = 'testScene';
        $treeName    = '蓝钻数据池/test场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[1], $tableField, $where, $treeName, 1);

        //蓝钻CP提现场景
        $table       = 'cost';
        $treeName    = '蓝钻数据池/蓝钻CP提现场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[2], $tableField, $where, $treeName, 1);

        //蓝钻CP交易场景
        $table       = 'trading';
        $treeName    = '蓝钻数据池/蓝钻CP交易场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[3], $tableField, $where, $treeName, 1);

        //ALA蓝钻场景
        $table       = 'ALA';
        $treeName    = '蓝钻数据池/ALA蓝钻场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[4], $tableField, $where, $treeName, 1);

        //上古彩发行场景
        $table       = 'SGCDistribution';
        $treeName    = '蓝钻数据池/上古彩发行场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[5], $tableField, $where, $treeName, 1);

        //爱上古游戏平台
        $table       = 'ASGGame';
        $treeName    = '蓝钻数据池/爱上古游戏平台';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[6], $tableField, $where, $treeName, 1);

        //过期蓝钻场景
        $table       = 'expired';
        $treeName    = '蓝钻数据池/过期蓝钻场景';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[7], $tableField, $where, $treeName, 1);

        //ALA蓝钻运营需求
        $table       = 'operation';
        $treeName    = '蓝钻数据池/ALA蓝钻运营需求';
        $returnArr[] = $this->_upExcelInsertRun($table, $data[8], $tableField, $where, $treeName, 1);

        return json_encode($returnArr);
    }
    //excel传过来的数据 进行%化 如果是数值 就转化 不是就原样
    public function percentFormat($value)
    {
        $lastData = is_numeric($value) ? sprintf("%01.2f", $value * 100) . '%' : $value;
        return $lastData;
    }
    //对0.3000000001这样的数据 处理 服务器上从excel读数据 会变成这样
    public function numberFormat($value)
    {
        if (is_numeric($value)) {
            if (ceil($value) != $value) {
                $value = sprintf("%01.2f", $value);
            }
        }
        return $value;
    }
    public function formatData($str = '')
    {
        //$str = '20121233';
        $fStr = substr($str, 0, 4);
        $sStr = substr($str, 4, 2);
        $tStr = substr($str, 6, 2);
        return $fStr . '-' . $sStr . '-' . $tStr;
        //$str = ;
    }
}

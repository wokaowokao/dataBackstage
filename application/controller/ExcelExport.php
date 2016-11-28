<?php
namespace app\controller;
use think\Db;
use think\Controller;
require_once './vendor/PHPExcel/PHPExcel/IOFactory.php';
class ExcelExport extends Common
{
    protected static $excelCol = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',];
    /**
     * excel下载 入口方法 下载excel 不支持字段排序 都以时间排序下载
     * @access public
     * @param String     $table 读取数据库表名
     * @param integer    $dateType 导出时间类型 0默认 1今 2昨 3周 4月 5范围 6字段排序
     * @param String     $form  范围开始时间
     * @param String     $to 范围结束时间
     * @param String     $title 名称如 用户特征
     * @return void
     */
    public function excute($table,$dateType,$from = '',$to = '',$title = '',$table = '',$searchStr = '',$searchId = '',$searchDuanType = ''){
        //echo $searchStr.'</br>';
        $from = $from == '*' ? '' : $from;
        $to = $to =='*' ? '' : $to;
        $searchStr = urldecode(base64_decode($searchStr));
        //$searchDuanType = urldecode(base64_decode($searchDuanType));
        //echo $searchStr.'</br>';exit;
        if($table == 'testScene' || $table == 'cost' || $table == 'trading' || $table == 'ALA' || $table == 'ASGGame' || $table == 'expired' || $table == 'operation' || $table == 'SGCDistribution'){
             $dataTitle = config('excel._table')['pool'];
        }else{
            $dataTitle = config('excel._table')[$table];
        }
        $order = 'date desc';
        //echo $searchStr;exit;

        $where = $this->getWhere($dateType,$from,$to,$table,$searchStr,$searchId,$searchDuanType);
        if($table == 'behaviourInfo') $table = 'behaviour_info';
        if($table == 'tbActivity') $table = 'tb_activity';
        if($table == 'baiduVisit') $table = 'baidu_visit';
        if($dateType == '3'){
            $data = Db::table($table)
                ->where($where)
                ->order($order)->limit(0,7)
                ->select();
        }elseif($dateType == '4'){
            $data = Db::table($table)
                ->where($where)
                ->order($order)->limit(0,30)
                ->select();
        }elseif($dateType == '0'){
            $data = [];
        }else{
            $data = Db::table($table)
                ->where($where)
                ->order($order)
                ->select();
        }
        //echo Db::getLastSql();exit;
        foreach ($data as $key => $value) {
            unset($data[$key]['id']);
        }
        //var_dump($data);
        $excelName = $this->getExcelName($title,$dateType,$from,$to);
        $this->download($excelName,$dataTitle,$data,$table);
    }
    /**
     *  生成excel下载名称 如 用户特征_2016.12.21-2016.12.24 周和月不包含今天
     * @access private
     * @param String     $title 名称如 用户特征
     * @param integer    $dateType 导出时间类型 0默认 1今 2昨 3周 4月 5范围 6字段排序
     * @param String     $form  范围开始时间
     * @param String     $to 范围结束时间
     * @return String
     */
    private function getExcelName($title = '',$dateType = 0,$from = '',$to = ''){
        $today = date('Y-m-d');
        $lastDay = date('Y-m-d',strtotime('-1 day'));
        if($dateType == 1) return $title.'_'.$today;
        if($dateType == 2) return $title.'_'.$lastDay;
        if($dateType == 3) return $title;
        if($dateType == 4) return $title;
        if($dateType == 5) return $title.'_'.$from.'_'.$to;
        return $title;
    }
    private function download($excelName,$dataTitle = [],$data = [],$table = '')
    {
        set_time_limit(0);
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel = $this->setDownLoadExcelTitle($objPHPExcel,$table,$dataTitle);
        $objPHPExcel = $this->setDownLoadExcelData($objPHPExcel,$table,$data);
        $objPHPExcel->getActiveSheet()->setTitle('记录');
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$excelName.xls");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 生成excel下载的表头部分
     * @access public
     * @param Object     $objPHPExcel excel对象
     * @param String    $table 表名
     * @param String     $dataTitle 表头部分
     * @return excelObject
     */
    public function setDownLoadExcelTitle($objPHPExcel,$table,$dataTitle) {
        $arr = self::$excelCol;
        switch ($table) {
            case 'behaviour_info':
                //单元格值设置
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $dataTitle[0][0]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', $dataTitle[0][1]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', $dataTitle[0][2]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', $dataTitle[0][3]);
                //单元格居中
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //单元格合并
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A2');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:F1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G1:I1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J1:L1');
                foreach ($dataTitle[1] as $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$key+1].'2', $value);
                }
                break;
            case 'ffrom':
                //单元格值设置
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $dataTitle[0][0]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', $dataTitle[0][1]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', $dataTitle[0][2]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', $dataTitle[0][3]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', $dataTitle[0][4]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', $dataTitle[0][5]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', $dataTitle[0][6]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', $dataTitle[0][7]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', $dataTitle[0][8]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', $dataTitle[0][9]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', $dataTitle[0][10]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', $dataTitle[0][11]);
                //单元格居中
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('K1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('N1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('Q1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('T1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('W1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('Z1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('AC1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('AF1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //单元格合并
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A2');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:D1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E1:G1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:J1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K1:M1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N1:P1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q1:S1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('T1:V1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W1:Y1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z1:AB1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AC1:AE1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AF1:AH1');

                foreach ($dataTitle[1] as $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$key+1].'2', $value);
                }
                break;
            case 'step':
                //单元格值设置
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $dataTitle[0][0]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', $dataTitle[0][1]);

                //单元格居中
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //单元格合并
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A2');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:F1');
                foreach ($dataTitle[1] as $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$key+1].'2', $value);
                }
                break;
            case 'feature':
                //单元格值设置
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $dataTitle[0][0]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', $dataTitle[0][1]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', $dataTitle[0][2]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', $dataTitle[0][3]);
                //单元格居中
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //单元格合并
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A2');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:C1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D1:E1');
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F1:J1');
                foreach ($dataTitle[1] as $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$key+1].'2', $value);
                }
                break;
            default:
                foreach ($dataTitle as $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$key].'1', $value);
                }
                break;
        }
        return $objPHPExcel;
    }
    /**
     * 生成excel下载的数据部分
     * @access public
     * @param Object     $objPHPExcel excel对象
     * @param String    $table 表名
     * @param String     $data 数据部分
     * @return excelObject
     */
    public function setDownLoadExcelData($objPHPExcel,$table,$data) {
        $arr = self::$excelCol;
        foreach ($data as $key => $value) {
            $_kk = 0;
            foreach ($value as $k => $v) {
                if($k == 'id') break;
                //部分表数据从3条开始
                if($table == 'behaviour_info' || $table == 'ffrom'  || $table == 'step' || $table == 'feature' ){
                    $_k = $key + 3;
                }else{
                    $_k = $key + 2;
                }
                //echo $arr[$_kk].$_k;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$_kk].$_k, $v);
                $_kk++;
            }
        }
        return $objPHPExcel;
    }
}
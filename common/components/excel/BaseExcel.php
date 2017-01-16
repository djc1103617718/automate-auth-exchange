<?php

namespace common\components\excel;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

$vendor = Yii::getAlias('@vendor');
require_once($vendor.'/PHPExcel/Classes/PHPExcel.php');

class BaseExcel
{
    private $instance = null;
    public $subject='please-set-subject';
    public $sheetWidth = 10;
    public $fileName = 'temp-filename';
    public $sheetHeader = [];
    public $sheetCellList = [
        'A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ];

    public function __construct()
    {
        $this->instance = new \PHPExcel();
    }

    public function getExcelObject()
    {
        if (!$this->instance) {
            throw new Exception('Not Found Instance');
        }
        return $this->instance;
    }

    private function getSheetHeader($item)
    {
        if (!$this->sheetHeader) {
            if (is_object($item)) {
                $headers = array_keys($item->attributes);
            } elseif (is_array($item)) {
                $headers = array_keys($item);
            } else {
                throw new Exception('无效的数据模型');
            }

            if (!$headers) {
                throw new Exception('无效的数据模型');
            }
            $this->sheetHeader = $headers;
        }
        return $this->sheetHeader;
    }

    private function getSheetValues($item)
    {
        if (is_object($item)) {
            $values = array_values($item->attributes);
        } elseif (is_array($item)) {
            $values = array_values($item);
        } else {
            throw new Exception('无效的数据模型');
        }

        if (!$values) {
            throw new Exception('无效的数据模型');
        }
        return $values;
    }

    /**
     * 通用的excel表格导出
     * @param object|array $dataModel
     */
    public function run($dataModel)
    {
        $phpExcel = $this->instance;
        $n = 0;

        foreach ( $dataModel as $product )
        {
            //报表头的输出
            $phpExcel->getActiveSheet()->mergeCells('B1:G1');
            $phpExcel->getActiveSheet()->setCellValue('B1',$this->subject);

            $phpExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $phpExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $phpExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
            $phpExcel->setActiveSheetIndex(0)->getStyle('G2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $header = $this->getSheetHeader($product);
            $phpExcel->setActiveSheetIndex(0)->setCellValue($this->sheetCellList[0].'3' ,'#');
            $phpExcel->getActiveSheet()->getColumnDimension($this->sheetCellList[0])->setWidth($this->sheetWidth);
            foreach ($header as $key => $h) {
                $key = $key + 1;
                $phpExcel->setActiveSheetIndex(0)->setCellValue($this->sheetCellList[$key].'3' ,$h);
                $phpExcel->getActiveSheet()->getColumnDimension($this->sheetCellList[$key])->setWidth($this->sheetWidth);
            }

            //明细的输出
            $attributeValues = $this->getSheetValues($product);
            $phpExcel->getActiveSheet()->setCellValue($this->sheetCellList[0].($n+4), $n+1);
            foreach ($attributeValues as $key => $v) {
                $key = $key + 1;
                $phpExcel->getActiveSheet()->setCellValue($this->sheetCellList[$key].($n+4) ,$v);
            }
            $n = $n +1;
        }
        $phpExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $phpExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$this->fileName.'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($phpExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 特定模版的excel表格导出
     * @param string $templateClassName @模版类名
     * @param object|array $dataModel
     */
    public function templateRun($templateClassName, $dataModel)
    {
        var_dump($templateClassName);
    }
}
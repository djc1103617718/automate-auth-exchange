<?php

namespace common\components\excel\templateExcel;

use yii\base\Object;

class TestExcel extends Object
{
    public function excelObj($dataProvider)
    {
        $phpExcel = $this->instance;
        $page_size = $dataProvider->getPagination()->pageSize;
        $dataProvider->setPagination(false);
        $data = $dataProvider->getModels();
        $count = $dataProvider->getTotalCount();
        $page_count = (int)($count/$page_size) +1;
        $current_page = 0;
        $n = 0;
        foreach ( $data as $product )
        {
            if ( $n % $page_size === 0 )
            {
                $current_page = $current_page +1;

                //报表头的输出
                $phpExcel->getActiveSheet()->mergeCells('B1:G1');
                $phpExcel->getActiveSheet()->setCellValue('B1','产品信息表');

                $phpExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
                $phpExcel->setActiveSheetIndex(0)->getStyle('B1')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $phpExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
                $phpExcel->setActiveSheetIndex(0)->setCellValue('G2','第'.$current_page.'/'.$page_count.'页');
                $phpExcel->setActiveSheetIndex(0)->getStyle('G2')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                //表格头的输出
                $phpExcel->getActiveSheet()->getColumnDimension('A');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('B3','编号');
                $phpExcel->getActiveSheet()->getColumnDimension('B');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('C3','名称');
                $phpExcel->getActiveSheet()->getColumnDimension('C');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('D3','生产厂家');
                $phpExcel->getActiveSheet()->getColumnDimension('D');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('E3','单位');
                $phpExcel->getActiveSheet()->getColumnDimension('E');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('F3','单价');
                $phpExcel->getActiveSheet()->getColumnDimension('F');
                $phpExcel->setActiveSheetIndex(0)->setCellValue('G3','在库数');
                $phpExcel->getActiveSheet()->getColumnDimension('G');

                //设置居中
                $phpExcel->getActiveSheet()->getStyle('B3:G3')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                //设置边框
                $phpExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $phpExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $phpExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $phpExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $phpExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

                //设置颜色
                $phpExcel->getActiveSheet()->getStyle('B3:G3')->getFill()
                    ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            }

            //明细的输出
            $phpExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$product->deviceid);
            $phpExcel->getActiveSheet()->setCellValue('C'.($n+4) ,$product->nouseid);
            $phpExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$product->last_job_type);
            $phpExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$product->last_connect_time);
            $phpExcel->getActiveSheet()->setCellValue('F'.($n+4) ,$product->account);
            $phpExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$product->wechat);
            //设置边框
            $currentRowNum = $n+4;
            $phpExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $phpExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $phpExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $phpExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $phpExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $n = $n +1;
        }

        //设置分页显示
        //$phpExcel->getActiveSheet()->setBreak( 'I55' , \PHPExcel_Worksheet::BREAK_ROW );
        //$phpExcel->getActiveSheet()->setBreak( 'I10' , \PHPExcel_Worksheet::BREAK_COLUMN );
        $phpExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $phpExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);


        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'产品信息表-'.date("Y年m月j日H时i分s秒").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($phpExcel,'Excel5');
        $objWriter->save('php://output');
    }
}
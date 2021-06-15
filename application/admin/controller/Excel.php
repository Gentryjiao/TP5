<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Loader;
class Excel extends Controller
{
    //生成excel
    public function creste_excel(){
        //如果使用thinkphp5.1框架，则直接use即可
        //实例化phpexcel对象
        vendor('PHPExcel.PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.PHPExcel.PHPExcel_IOFactory');

        $objPHPExcel = new \PHPExcel();

        //设置Excel属性
        $objPHPExcel->getProperties()
            ->setCreator("Maarten Balliauw")					//创建人
            ->setLastModifiedBy("Maarten Balliauw")				//最后修改人
            ->setTitle("Office 2007 XLSX Test Document")		//设置标题
            ->setSubject("Office 2007 XLSX Test Document")		//设置主题
            ->setDescription("Test document ")					//设置备注
            ->setKeywords( "office 2007 openxml php")			//设置关键字
            ->setCategory( "Test result file");					//设置类别

        // 给表格添加数据
        $objPHPExcel->setActiveSheetIndex(0)             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
        ->setCellValue( 'A1', 'Group Code / Policy ID' )
            ->setCellValue( 'B1', 'FirstName' )
            ->setCellValue( 'C1', 'Middle Initial' )
            ->setCellValue( 'D1', 'LastName' )
            ->setCellValue( 'E1', 'Address1' )
            ->setCellValue( 'F1', 'Address 2' )
            ->setCellValue( 'G1', 'City' )
            ->setCellValue( 'H1', 'State' );

        //激活当前表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码

        //最后只需要生成Excel或者提示下载即可
        //生成Excel，并自定义保存路径
        //"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        $time=time();
        $name=date('Y-m-d',$time);
        $objWriter->save('public/'.$name.'.xls');
        return json(['code'=>1,'msg'=>'ok','url'=>addavatarUrl('/public/'.$name.'.xls')]);

        //弹出提示下载文件
//        header('pragma:public');
//        header("Content-Disposition:attachment;filename=name.xls");
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save( 'php://output');
    }

}
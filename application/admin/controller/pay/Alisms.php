<?php

namespace app\admin\controller\pay;

use think\Request;
use think\Db;
use think\Controller;
use service\JsonService;
use service\UtilService as Util;
use service\UploadService as Upload;
use think\Url;
/**
 * 登录验证控制器
 * Class Login
 * @package app\admin\controller
 */
class Alisms extends Controller
{
    public function index()
    {
        $alisms=Db('alisms_config')->where('id',1)->find();
        $this->assign('alisms',$alisms);
        return $this->fetch();
    }

    public function sub(Request $re){
        $post=$re->post();
        $id=1;
        $data=[
            'id'=>$id,
            'accessKeyId'=>$post['accessKeyId'],
            'accessKeySecret'=>$post['accessKeySecret'],
            'sign'=>$post['sign'],
            'code'=>$post['code'],
        ];
        $count=Db('alisms_config')->where('id',$data['id'])->count();
        if($count){
            $res=Db('alisms_config')->where('id',$data['id'])->update($data);
        }else{
            $res=Db('alisms_config')->insert($data);
        }
        if($res){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

    public function index1()
    {
        $alisms=Db('alisms_config')->where('id',2)->find();
        $this->assign('alisms',$alisms);
        return $this->fetch();
    }

    public function sub1(Request $re){
        $post=$re->post();
        $id=2;
        $data=[
            'id'=>$id,
            'accessKeyId'=>$post['accessKeyId'],
            'accessKeySecret'=>$post['accessKeySecret'],
            'sign'=>$post['sign'],
            'code'=>$post['code'],
        ];
        $count=Db('alisms_config')->where('id',$data['id'])->count();
        if($count){
            $res=Db('alisms_config')->where('id',$data['id'])->update($data);
        }else{
            $res=Db('alisms_config')->insert($data);
        }
        if($res){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

}
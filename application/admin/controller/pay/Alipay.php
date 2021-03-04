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
class Alipay extends Controller
{
    public function index()
    {
        $alipay=Db('alipay_config')->where('id',1)->field('app_id,alipay_public_key,merchant_private_key,notify_url,return_url,charset,sign_type,gatewayUrl')->find();
        $this->assign('alipay',$alipay);
        return $this->fetch();
    }

    public function sub(Request $re){
        $post=$re->post();
        $id=1;

        $data=[
            'id'=>$id,
            'app_id'=>$post['app_id'],
            'alipay_public_key'=>$post['alipay_public_key'],
            'merchant_private_key'=>$post['merchant_private_key'],
            'notify_url'=>$post['notify_url'],
            'return_url'=>$post['return_url'],
            'charset'=>$post['charset'],
            'sign_type'=>$post['sign_type'],
            'gatewayUrl'=>$post['gatewayUrl'],
        ];
        $count=Db('alipay_config')->where('id',$data['id'])->count();
        if($count){
            $res=Db('alipay_config')->where('id',$data['id'])->update($data);
        }else{
            $res=Db('alipay_config')->insert($data);
        }
        if($res){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

}
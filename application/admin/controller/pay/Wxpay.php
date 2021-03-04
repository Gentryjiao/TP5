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
class Wxpay extends Controller
{
    public function index(Request $re)
    {
        $wxpay=Db('wxpay_config')->where('id',1)->field('appid,mch_id,body,notify_url,trade_type,key')->find();
        $this->assign('wxpay',$wxpay);
        return $this->fetch();
    }

    public function sub(Request $re){
        $post=$re->post();
        $id=1;

        $data=[
            'id'=>$id,
            'appid'=>$post['appid'],
            'mch_id'=>$post['mch_id'],
            'body'=>$post['body'],
            'notify_url'=>$post['notify_url'],
            'trade_type'=>$post['trade_type'],
            'key'=>$post['key'],
        ];
        $count=Db('wxpay_config')->where('id',$data['id'])->count();
        if($count){
            $res=Db('wxpay_config')->where('id',$data['id'])->update($data);
        }else{
            $res=Db('wxpay_config')->insert($data);
        }
        if($res){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

}
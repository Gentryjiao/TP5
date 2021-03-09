<?php

namespace app\admin\controller;

use think\Request;
use think\Db;
use think\Controller;
use service\JsonService;
use service\UtilService as Util;
use service\JsonService as Json;
use service\UploadService as Upload;
use think\Url;
use app\admin\model\Image as ImageModel;
use app\admin\model\ImageType as ImageTypeModel;
/**
 * 登录验证控制器
 * Class Login
 * @package app\admin\controller
 */
class Image extends Controller
{
    //oss上传
    public function upload_oss(){
        include EXTEND_PATH."aliyun-oss-php-sdk/autoload.php";
        include EXTEND_PATH."aliyun-oss-php-sdk/src/OSS/OssClient.php";
        include EXTEND_PATH."aliyun-oss-php-sdk/src/OSS/Core/OssException.php";


        if (is_file(__DIR__ . '/../autoload.php')) {
            require_once __DIR__ . '/../autoload.php';
        }
        if (is_file(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
        }

        if($this->request->file('file')){
            $file = $this->request->file('file');
            $www= $_FILES['file'];
        }else{
            $res['code']=1;
            $res['msg']='没有上传文件';
            return json($res);
        }

        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
        $accessKeyId = "LTAI4GDKgbzQC6qECDdAsAuc";
        $accessKeySecret = "kdOE2shmnXrRlbe3ck5cMHHBQI5ckh";
        // ECS 的经典网络访问（内网）
        $endpoint = "oss-cn-beijing-internal.aliyuncs.com";
        // 外网访问
        $waiwang = "http://meihuaquan.oss-cn-beijing.aliyuncs.com/images/";
        // 存储空间名称
        $bucket= "meihuaquan";

        $ext = substr($www['name'],strrpos($www['name'],'.')+1); // 上传文件后缀
        $dst = 'images/'.time().rand(00,99).'.'.$ext;
        //获取对象
        $auth = new \OssClient($accessKeyId,$accessKeySecret,$endpoint);

        try {
            $auth->setTimeout(5000);
            // 设置建立连接的超时时间，单位秒，默认10秒。
            $auth->setConnectTimeout(600);
            //上传图片
            $result  = $auth->uploadFile($bucket,$dst,$www['tmp_name']);
            $res['msg'] = '上传成功!';
            $res['path']=$waiwang.basename($result['info']['url']);
            return json($res);
        } catch (OssException $e) {
            return $this->error($e->getMessage());
        }
    }

    public function index(Request $re,$imgtype=1)
    {
        $this->assign('imgtype',$imgtype); //2多图
        $type=Db('image_type')->select();
        $this->assign('type',$type);
        $where = Util::getMore([
            ['type',$this->request->param('type','')],
            ['page',],
            ['limit',],
            ['order','id'],
        ]);
        $data=ImageModel::getList($where);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update1(Request $request, $id)
    {
        $data = Util::getMore([
            ['value',$request->post('value','')],
            ['field',$request->post('field')],
        ]);
        if($data['value']=='') return json(['status'=>'error','msg'=>'值不能为空']);
        if(ImageTypeModel::edit([$data['field']=>$data['value']],$id)){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

    public function imglist(){
        return view('list');
    }

    public function add_type(){
        $where = Util::getMore([
            ['page',1],
            ['limit',20],
            ['pid','0'],
            ['order','id'],
        ]);
        $data=ImageTypeModel::getlist($where);
        $this->assign('type',$data['data']);
        return view();
    }

    public function save_type(Request $re){
        $post=$re->post();
        $data=[
            'pid'=>$post['pid'],
            'name'=>$post['name'],
        ];
        $res=ImageTypeModel::set($data);
        if($res){
            return json(['status'=>'success','msg'=>'保存成功']);
        }else{
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }

    public function add_img(){
        $data=Db('image_type')->select();
        $this->assign('data',$data);
        return view();
    }

    public function save_img(Request $re){
        $post=$re->post();
        // return json($post);
        if(empty($post['type'])){
            return json(['status'=>'error','msg'=>'请选择分类']);
        }
        if(empty($post['image'])){
            return json(['status'=>'error','msg'=>'请上传图片']);
        }
        Db::startTrans();
        try{
            foreach($post['image'] as $k=>$v){
                $data=[
                    'type'=>$post['type'],
                    'image'=>$v,
                ];
                $res=ImageModel::set($data);
            }
            // 提交事务
            Db::commit();
            return json(['status'=>'success','msg'=>'保存成功']);

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['status'=>'error','msg'=>'保存失败']);
        }
    }


    public function getlist(){
        $where = Util::getMore([
            ['page',1],
            ['limit',20],
            ['pid','0'],
            ['order','id'],
        ]);
        $data=ImageTypeModel::getlist($where);
        return json($data);
    }

    public function getlist1(){
        $where = Util::getMore([
            ['page',1],
            ['limit',20],
            ['pid',$this->request->param('id')],
            ['order','id'],
        ]);
        $data=ImageTypeModel::getlist($where);
        return json($data);
    }

    public function getlist2(){
        $where = Util::getMore([
            ['page',1],
            ['limit',20],
            ['order','id'],
            ['type',$this->request->param('id')],
        ]);
        $data=ImageModel::getlist($where);
        return json($data);
    }

    // /**
    //  * 上传图片
    //  * @return \think\response\Json
    //  */
    // public function upload()
    // {
    //     $res = Upload::image('file','store/product/'.date('Ymd'));
    //     if($res){
    //         return json(['status'=>'success','msg'=>'上传成功','path'=>$res['thumb_path']]);
    //     }else{
    //         return json(['status'=>'error','msg'=>'上传失败']);
    //     }
    // }

    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function upload()
    {
        $res = Upload::file('file','store/product/'.date('Ymd'));
        if($res){
            return json(['status'=>'success','msg'=>'上传成功','path'=>$res->filePath]);
        }else{
            return json(['status'=>'error','msg'=>'上传失败']);
        }
    }

    /**
     * 上传图片
     * @return \think\response\Json
     */
    public function layuiupload()
    {
        $res = Upload::file('file','store/product/'.date('Ymd'));
        if($res){
            return json(['code'=>0,'msg'=>'上传成功','data'=>['src'=>$res->filePath]]);
        }else{
            return json(['code'=>1,'msg'=>'上传失败']);
        }
    }


    public function delete(Request $re){
        $id=$re->post('id');
        Db::startTrans();
        try{
            $sele=Db('image_type')->where('pid',$id)->select();
            foreach($sele as $k=>$v){
                $image=Db('image')->where('type',$v['id'])->find();
                if(file_exists(ROOT_PATH.$image['image'])){
                    unlink(ROOT_PATH.$image['image']);
                }
                Db('image')->where('id',$image['id'])->delete();
                Db('image_type')->where('id',$v['id'])->delete();
            }
            Db('image_type')->where('id',$id)->delete();
            // 提交事务
            Db::commit();
            return json(['status'=>'success','msg'=>'删除成功','url'=>'/admin/store.product/index']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['status'=>'error','msg'=>'删除失败']);
        }
    }
    
    public function deleteimg(Request $re){
        $id=$re->post('id');
        Db::startTrans();
        try{
            $img=Db('image')->where('id',$id)->find();
            if(file_exists(ROOT_PATH.$img['image'])){
                unlink(ROOT_PATH.$img['image']);
            }
            Db('image')->where('id',$id)->delete();
            // 提交事务
            Db::commit();
            return json(['status'=>'success','msg'=>'删除成功','url'=>'/admin/store.product/index']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['status'=>'error','msg'=>'删除失败']);
        }
    }
}
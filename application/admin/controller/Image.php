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

//阿里云Oss
use OSS\OssClient;
use OSS\Core\OssException;
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
        $date=date('Y-m-d',time());
        // 外网访问
        $waiwang = "http://meihuaquan.oss-cn-beijing.aliyuncs.com/images/".$date."/";
        // 存储空间名称
        $bucket= "meihuaquan";

        $ext = substr($www['name'],strrpos($www['name'],'.')+1); // 上传文件后缀
        $dst = 'images/'.$date.'/'.time().rand(00,99).'.'.$ext;
        //获取对象
        $auth = new OssClient($accessKeyId,$accessKeySecret,$endpoint);

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

    //阿里云分片上传
    public function upload_sheet(){
        include EXTEND_PATH."aliyun-oss-php-sdk/autoload.php";

        // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录RAM控制台创建RAM账号。
        $accessKeyId = "LTAI5tEAE2nvKa5rQwJxBwEk";
        $accessKeySecret = "kMTXf6zZISYhUfeiSRwPOCm7a1UGV8";
        // ECS 的经典网络访问（内网）
        $endpoint = "oss-cn-beijing-internal.aliyuncs.com";
        // Endpoint以杭州为例，其它Region请按实际情况填写。
        // $endpoint = "http://oss-cn-hangzhou.aliyuncs.com";
        $bucket= "zsddoss";

        if($this->request->file('file')){
            $file = $this->request->file('file');
            $www= $_FILES['file'];
        }else {
            $res['code'] = 1;
            $res['msg'] = '没有上传文件';
            return json($res);
        }
        $date=date('Y-m-d',time());
        // 外网访问
        $waiwang = "http://zsddoss.oss-cn-beijing.aliyuncs.com/video/".$date."/";
        $object =substr($www['name'],strrpos($www['name'],'.')+1);;
        $uploadFile = 'video/'.$date.'/'.time().rand(00,99).'.'.$object;
        /**
         *  步骤1：初始化一个分片上传事件，获取uploadId。
         */
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            //返回uploadId。uploadId是分片上传事件的唯一标识，您可以根据uploadId发起相关的操作，如取消分片上传、查询分片上传等。
            $uploadId = $ossClient->initiateMultipartUpload($bucket, $object);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": initiateMultipartUpload OK" . "\n");
        /*
         * 步骤2：上传分片。
         */
        $partSize = 10 * 1024 * 1024;
        $uploadFileSize = filesize($uploadFile);
        $pieces = $ossClient->generateMultiuploadParts($uploadFileSize, $partSize);
        $responseUploadPart = array();
        $uploadPosition = 0;
        $isCheckMd5 = true;
        foreach ($pieces as $i => $piece) {
            $fromPos = $uploadPosition + (integer)$piece[$ossClient::OSS_SEEK_TO];
            $toPos = (integer)$piece[$ossClient::OSS_LENGTH] + $fromPos - 1;
            $upOptions = array(
                // 上传文件。
                $ossClient::OSS_FILE_UPLOAD => $uploadFile,
                // 设置分片号。
                $ossClient::OSS_PART_NUM => ($i + 1),
                // 指定分片上传起始位置。
                $ossClient::OSS_SEEK_TO => $fromPos,
                // 指定文件长度。
                $ossClient::OSS_LENGTH => $toPos - $fromPos + 1,
                // 是否开启MD5校验，true为开启。
                $ossClient::OSS_CHECK_MD5 => $isCheckMd5,
            );
            // 开启MD5校验。
            if ($isCheckMd5) {
                $contentMd5 = OssUtil::getMd5SumForFile($uploadFile, $fromPos, $toPos);
                $upOptions[$ossClient::OSS_CONTENT_MD5] = $contentMd5;
            }
            try {
                // 上传分片。
                $responseUploadPart[] = $ossClient->uploadPart($bucket, $object, $uploadId, $upOptions);
                return  'aaa'.time();
            } catch(OssException $e) {
                printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
        }
        // $uploadParts是由每个分片的ETag和分片号（PartNumber）组成的数组。
        $uploadParts = array();
        foreach ($responseUploadPart as $i => $eTag) {
            $uploadParts[] = array(
                'PartNumber' => ($i + 1),
                'ETag' => $eTag,
            );
        }
        /**
         * 步骤3：完成上传。
         */
        try {
            // 执行completeMultipartUpload操作时，需要提供所有有效的$uploadParts。OSS收到提交的$uploadParts后，会逐一验证每个分片的有效性。当所有的数据分片验证通过后，OSS将把这些分片组合成一个完整的文件。
            $ossClient->completeMultipartUpload($bucket, $object, $uploadId, $uploadParts);
            $res['msg'] = '上传成功!';
            $res['path']=$waiwang.$object;
            return json($res);
        }  catch(OssException $e) {
            printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        printf(__FUNCTION__ . ": completeMultipartUpload OK\n");
    }

    //oss上传
    public function upload_video(){
        include EXTEND_PATH."aliyun-oss-php-sdk/autoload.php";

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
        $date=date('Y-m-d',time());
        // 外网访问
        $waiwang = "http://meihuaquan.oss-cn-beijing.aliyuncs.com/video/".$date."/";
        // 存储空间名称
        $bucket= "meihuaquan";

        $ext = substr($www['name'],strrpos($www['name'],'.')+1); // 上传文件后缀
        $dst = 'video/'.$date.'/'.time().rand(00,99).'.'.$ext;
        //获取对象
        $auth = new OssClient($accessKeyId,$accessKeySecret,$endpoint);

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
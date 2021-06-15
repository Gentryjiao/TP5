<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Request;
use think\Cache;

/*
 * 微信生成分享二维码
 * */
class Wxqrcode extends Controller
{

    public function sendCmd($url,$data)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $tmpInfo; // 返回数据
    }

    public function miniewm($path,$id){
        $myfile=fopen(ROOT_PATH.$path,'w+');
        $config['appid'] = 'wxc4c9eb9b2452e386';
        $config['secret'] = 'd2f8105a243e55196edef4805c8af04f';
        $url_access_token=Cache::get('url_access_token');

        if(!$url_access_token){
            $url_access_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$config['appid'].'&secret='.$config['secret'];
            $url_access_token=Cache::set('url_access_token',$url_access_token);
        }
        $json_access_token = $this -> sendCmd($url_access_token,array());
        $arr_access_token = json_decode($json_access_token,true);
        $access_token = $arr_access_token['access_token'];
        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$access_token;
        $result = $this -> sendCmd($url,'{"path": "/pages/index/xiang?id='.$id.'", "width": 430}');
        fwrite($myfile,$result);
        fclose($myfile);
        return $path;
    }

}



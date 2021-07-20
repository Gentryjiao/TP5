<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
class Wxpay extends Controller
{
    private $config = array (
        //商户号
        'mchid'=>'',
        //应用ID,您的APPID
        'appid' => "",
        //异步通知地址*
        'notify_url' => "",
        //商户私钥*
        'apiv3_private_key' => "",
        //证书序列号
        'xlid'=>''
    );

    //统一下单
    public function wxpay(){
        // 官方提供网址
        $url = "https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi";
        $urlarr = parse_url($url); //拆解为：[scheme=>https,host=>api.mch.weixin.qq.com,path=>/v3/pay/transactions/native]
        $time = time(); //时间戳
        $noncestr = $this->getNonceStr();
        $appid = $this->config['appid'];//appID
        $mchid = $this->config['mchid'];//商户ID
        $xlid = $this->config['xlid'];//证书序列号 可在这个网址中查询 https://myssl.com/cert_decode.html
        $data = array();
        $data['appid'] = $appid;
        $data['mchid'] = $mchid;
        $data['description'] = '描述';//商品描述
        $data['out_trade_no'] = time().rand(1111,9999);//订单编号，订单号在微信支付里是唯一的
        $data['notify_url'] = $this->config['notify_url'];//需根据自己的情况修改回调接口，也可以为空
        $data['amount']['total'] = 1;//金额 单位 分
        $data['scene_info']['payer_client_ip'] = $_SERVER["REMOTE_ADDR"];;//场景ip
        $data['payer']['openid']="dasdaaaxxxxx"; //openid
        $data = json_encode($data); //变为json格式
        //签名，包含了$data数据、微信指定地址、随机数和时间
        $key = $this->getSign($data,$urlarr['path'],$noncestr,$time);
        //头部信息
        $token = sprintf('mchid="%s",serial_no="%s",nonce_str="%s",timestamp="%d",signature="%s"',$mchid,$xlid,$noncestr,$time,$key);
        $header  = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 '.$token
        );
        //向微信接口地址提交json格式的$data和header的头部信息，得到返回值
        $res = $this->curl_post_https($url,$data,$header);
        $prepay_id=json_decode($res,true)['prepay_id'];
        $paySign=$this->getWechartSign($appid,$time,$noncestr,'prepay_id='.$prepay_id);;
        $payData=[
            'timeStamp'=>$time,
            'nonceStr'=>$noncestr,
            'package'=>'prepay_id='.$prepay_id,
            'signType'=>'RSA',
            'paySign'=>$paySign
        ];
        return_msg(200,'ok',$payData);
    }

    //获取随机字符串
    public function getNonceStr(){
        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
        return $name;
    }

    //微信支付签名
    function getSign($data=array(),$url,$randstr,$time){
        $str = "POST"."\n".$url."\n".$time."\n".$randstr."\n".$data."\n";
        $key = file_get_contents('apiclient_key.pem');//在商户平台下载的秘钥
        $str = $this->getSha256WithRSA($str,$key);
        return $str;
    }
    //调起支付的签名
    function getWechartSign($appid,$timeStamp,$noncestr,$prepay_id){
        $str = $appid."\n".$timeStamp."\n".$noncestr."\n".$prepay_id."\n";
        $key = file_get_contents('apiclient_key.pem');
        $str = $this->getSha256WithRSA($str,$key);
        return $str;
    }

    //加密
    public function getSha256WithRSA($content, $privateKey){
        $binary_signature = "";
        $algo = "SHA256";
        openssl_sign($content, $binary_signature, $privateKey, $algo);
        $sign = base64_encode($binary_signature);
        return $sign;
    }

    /* PHP CURL HTTPS POST */
    function curl_post_https($url,$data,$header){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在，如果出错则修改为0，默认为1
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }

    //微信回调
    public function notify(){
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $nonceStr = $data['resource']['nonce'];
        $associatedData = $data['resource']['associated_data'];
        $ciphertext = $data['resource']['ciphertext'];
        $ciphertext = base64_decode($ciphertext);
        $orderData = sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->config['apiv3_private_key']);
        $orderData = json_decode($orderData, true);
        $out_trade_no=$orderData['out_trade_no'];
        //$out_trade_no为订单号


        //应答微信支付已处理该订单的通知
        return ['code' => 'SUCCESS', 'message' =>'ok'];
    }


}
<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
class Wxpay extends Controller
{
    private $config = array (
        //商户号
        'mchid'=>'',
        //应用ID,您的APPID。*
        'appid' => "",
        //异步通知地址*
        'notify_url' => "",
        //商户私钥*
        'apiv3_private_key' => "",
        //证书序列号
        'xlid'=>'',
        //退款回调地址
        'refund_notify_url'=> "",
    );

    //统一下单
    public function wxpay(){
        $out_trade_no=$this->request->param('out_trade_no','');
        if(empty($order_sn)) return json(['code'=>202,'msg'=>'缺少参数order_sn']);

        // 官方提供网址
        $url = "https://api.mch.weixin.qq.com/v3/pay/transactions/jsapi";
        $urlarr = parse_url($url); //拆解为：[scheme=>https,host=>api.mch.weixin.qq.com,path=>/v3/pay/transactions/native]
        $time = time(); //时间戳
        $noncestr = $this->getNonceStr();
        $appid = $this->config['appid'];//appID
        $mchid = $this->config['mchid'];//商户ID
        $xlid = $this->config['xlid'];//证书序列号
        $data = array();
        $data['appid'] = $appid;
        $data['mchid'] = $mchid;
        $data['description'] = '商品描述';//商品描述
        $data['out_trade_no'] = $out_trade_no;//订单编号，订单号在微信支付里是唯一的
        $data['notify_url'] = $this->config['notify_url'];//需根据自己的情况修改回调接口，也可以为空
        $data['amount']['total'] = 1;//金额 单位 分
        $data['scene_info']['payer_client_ip'] = $_SERVER["REMOTE_ADDR"];;//场景ip
        $data['payer']['openid']='openid'; //openid
        $data = json_encode($data);
        //签名，包含了$data数据、微信指定地址、随机数和时间
        $key = $this->getSign($data,$urlarr['path'],$noncestr,$time);
        $token = sprintf('mchid="%s",serial_no="%s",nonce_str="%s",timestamp="%d",signature="%s"',$mchid,$xlid,$noncestr,$time,$key);
        //头部信息
        $header  = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 '.$token
        );
        //向微信接口地址提交json格式的$data和header的头部信息，得到返回值
        $res = $this->curl_post_https($url,$data,$header);
        $prepay_id=json_decode($res,true)['prepay_id'];
        $paySign=$this->getWechartSign($appid,$time,$noncestr,'prepay_id='.$prepay_id);
        $payData=[
            'timeStamp'=>$time,
            'nonceStr'=>$noncestr,
            'package'=>'prepay_id='.$prepay_id,
            'signType'=>'RSA',
            'paySign'=>$paySign
        ];
        return json(['code'=>200,'msg'=>'ok','data'=>$payData]);
    }

    //获取随机字符串
    public function getNonceStr(){
        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890";
        $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),32);
        return $name;
    }

    //微信支付签名
    function getSign($data=array(),$url,$randstr,$time){
        $str = "POST"."\n".$url."\n".$time."\n".$randstr."\n".$data."\n";
        $key = file_get_contents('apiclient_key.pem');//在商户平台下载的秘钥,读取到变量
        $str = $this->getSha256WithRSA($str,$key);
        return $str;
    }

    //调起支付的签名
    function getWechartSign($appid,$timeStamp,$noncestr,$prepay_id){
        $str = $appid."\n".$timeStamp."\n".$noncestr."\n".$prepay_id."\n";
        $key = file_get_contents('apiclient_key.pem');//在商户平台下载的秘钥,读取到变量
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
    public function curl_post_https($url,$data,$header){ // 模拟提交数据函数
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
        //php>7.1,为了使用这个扩展，你必须将extension=php_sodium.dll添加到php.ini
        if (function_exists('\sodium_crypto_aead_aes256gcm_is_available') && \sodium_crypto_aead_aes256gcm_is_available()) {
            //$APIv3_KEY就是在商户平台后端设置是APIv3秘钥
            $orderData = \sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->config['apiv3_private_key']);
            $orderData = json_decode($orderData, true);
            if ($orderData['trade_state']=='SUCCESS'){
                $order_sn=$orderData['out_trade_no']; //商户订单号
                $transaction_id=$orderData['transaction_id']; //微信订单号
                // 启动事务
                Db::startTrans();
                try{
                    /*业务处理*/

                    Db::commit();
                    //应答微信支付已处理该订单的通知
                    return json(['code' => 'SUCCESS', 'message' =>'ok']);
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    return json(['code' => 'ERROR', 'message' =>'no']);
                }
            }
        }
    }


    //退款
    public function Refund($transaction_id,$out_trade_no){ //商户订单号,微信生成的退款订单号 二选一即可
        $time=time();
        $out_refund_no=$time.rand(1111,9999);
        $refundData=[
            'out_refund_no'=>$out_refund_no,
            'reason'=>'商品退款',
            'notify_url'=>$this->config['refund_notify_url'],
            'funds_account'=>'AVAILABLE',
            'amount'=>[
                'refund'=>1, //退款标价金额，单位为分，可以做部分退款
                'total'=>1, //订单总金额，单位为分
                'currency'=>'CNY'
            ]
        ];
        if(!$transaction_id){ //商户订单号,微信生成的退款订单号 二选一即可
            if(!$out_trade_no){
                return ['code'=>0,'msg'=>'退款订单号不能为空'];
            }else{
                $refundData['out_trade_no']=$out_trade_no;
            }
        }else{
            $refundData['transaction_id']=$transaction_id;
        }
        $url='https://api.mch.weixin.qq.com/v3/refund/domestic/refunds';
        $urlarr = parse_url($url); //拆解为：[scheme=>https,host=>api.mch.weixin.qq.com,path=>/v3/pay/transactions/native]
        $mchid = $this->config['mchid'];//商户ID
        $xlid = $this->config['xlid'];//证书序列号
        $refundData=json_encode($refundData);
        $nonce = $this->getNonceStr();
        $key = $this->getSign($refundData,$urlarr['path'],$nonce,$time);
        $token = sprintf('mchid="%s",serial_no="%s",nonce_str="%s",timestamp="%d",signature="%s"',$mchid,$xlid,$nonce,$time,$key);
        $header  = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent:*/*',
            'Authorization: WECHATPAY2-SHA256-RSA2048 '.$token
        );
        $res=$this->curl_post_https($url,$refundData,$header);
        $res_array=json_decode($res,true);
        if(($res_array['status']=='PROCESSING' || $res_array['status']=='SUCCESS') && isset($res_array['status'])){
            return ['code'=>1,'msg'=>'退款成功'];
        }else{
            return ['code'=>0,'msg'=>$res_array['message']];
        }
    }

    //退款回调地址
    public function refund_notify(){
        $notifiedData = file_get_contents('php://input');
        $data = json_decode($notifiedData, true);
        $nonceStr = $data['resource']['nonce'];
        $associatedData = $data['resource']['associated_data'];
        $ciphertext = $data['resource']['ciphertext'];
        $ciphertext = base64_decode($ciphertext);
        //php>7.1,为了使用这个扩展，你必须将extension=php_sodium.dll添加到php.ini
        if (function_exists('\sodium_crypto_aead_aes256gcm_is_available') && \sodium_crypto_aead_aes256gcm_is_available()) {
            //$APIv3_KEY就是在商户平台后端设置是APIv3秘钥
            $orderData = \sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $associatedData, $nonceStr, $this->config['apiv3_private_key']);
            $orderData = json_decode($orderData, true);
            if ($orderData['refund_status']=='SUCCESS'){
                $transaction_id=$orderData['transaction_id']; //退款单号

                /*业务处理*/
                return json(['code'=>'SUCCESS','message'=>'成功']);
            }
        }
    }


}
<?php
namespace app\api\controller;
use think\Controller;
use think\Request;

class H5wxpay extends Controller
{
    /*
     * 1.登录微信支付商户平台，申请h5支付，https://pay.weixin.qq.com/index.php/core/home/login?return_url=%2F
     * 2.获取商户号，商户key，商户key在api设置里面，设置api密钥
     * 3.appid需要授权关联小程序或服务器类得appid
     * */
    public $appid ;
    public $mch_id;
    public $key;
    public $notify_url;
    public function __construct()
    {
        $this->appid = 'wxc83443a4eaf76509'; //APPID
        $this->mch_id = '1610947564'; //商户号
        $this->key = '755b12a443ae4d3dbb01b71fdd96cedf'; //商户key
        $this->notify_url = 'https://happy.kuaibana.com/wxpay/index'; //回调url,需在商户平台配置
    }

    /**
     * 下单方法
     * @param  array $params 下单参数
     */
    public function unifiedOrder(){
        $params=array();
        $params['body'] = '会员'; //商品描述
        $params['out_trade_no'] = time().rand(1111,9999); //订单号
        $params['total_fee'] = 1; //金额是以分为单位，除测试外，需乘以100
        $params['trade_type'] = 'MWEB';  //交易类型，h5支付，默认如此
        $params['scene_info'] = '{"h5_info": {"type":"Wap","wap_url": "https://happy.kuaibana.com","wap_name": "社交"}}';   //场景信息,h5固定
        $params['spbill_create_ip'] = $this->getIp();   //终端IP
        $params['appid'] = $this->appid;
        $params['mch_id'] = $this->mch_id;
        $params['nonce_str'] = $this->genRandomString();  //随机字符串
        $params['notify_url'] = $this->notify_url;  //通知地址
        //获取签名数据
        $params['sign'] = $this->MakeSign($params);   //签名
        $xml = $this->data_to_xml($params);
        $uri = 'https://api.mch.weixin.qq.com/pay/unifiedorder';    //请求地址
        $response = $this->postXmlCurl($uri,$xml);   //自定义封装的xml请求格式，文章最下面为参考postxml
        if( !$response ){
            return false;
        }
        $result = $this->xml_to_data( $response );
        if( !empty($result['result_code']) && !empty($result['err_code']) ){
            $result['err_msg'] = $this->error_code( $result['err_code'] );
        }
        if($result['result_code'] == 'SUCCESS' && $result['return_msg'] == 'OK'){
            //发起微信支付url
            $pay_url = $result['mweb_url'].'&redirect_url='.urlencode($this->notify_url);
            //使用该url调用支付
            return $pay_url;
        }else{
            return false;
        }
    }

    //支付成功通知地址
    public function index(){
        $xml = file_get_contents('php://input');//监听是否有数据传入
        if(!empty($xml)){
            //微信返回信息
            $data = $this->xml_to_data($xml);
            if($data['return_code'] == 'SUCCESS'){
                var_dump($data);
                $this->replyNotify();
            }
        }
    }

    /**
     * 查询订单信息
     * @param string $out_trade_no 订单号
     * @return array
     */
    public function orderQuery( $out_trade_no ){
        $params['appid'] = $this->appid;
        $params['mch_id'] = $this->mch_id;
        $params['nonce_str'] = $this->genRandomString();
        $params['out_trade_no'] = $out_trade_no;
        //获取签名数据
        $params['sign'] =  $this->MakeSign($params);
        $xml = $this->data_to_xml($params);
        $uri = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $response = $this->postXmlCurl($uri,$xml);
        if(!$response){
            return false;
        }
        $result = $this->xml_to_data( $response );
        if( !empty($result['result_code']) && !empty($result['err_code']) ){
            $result['err_msg'] = $this->error_code( $result['err_code'] );
        }
        return $result;
    }

    /**
     * 关闭订单
     * @param string $out_trade_no 订单号
     * @return array
     */
    public function closeOrder( $out_trade_no ){
        $params['appid'] = $this->appid;
        $params['mch_id'] = $this->mch_id;
        $params['nonce_str'] = $this->genRandomString();
        $params['out_trade_no'] = $out_trade_no;
        //获取签名数据
        $params['sign'] = $this->MakeSign( $params );
        $xml = $this->data_to_xml($params);
        $response = $this->postXmlCurl($xml, self::API_URL_PREFIX.self::CLOSEORDER_URL);
        if( !$response ){
            return false;
        }
        $result = $this->xml_to_data( $response );
        return $result;
    }

    /**
     *
     * 获取支付结果通知数据
     * return array
     */
    public function getNotifyData(){
        //获取通知的数据
        $xml = file_get_contents('php://input');
        $data = array();
        if( empty($xml) ){
            return false;
        }
        $data = $this->xml_to_data( $xml );
        if( !empty($data['return_code']) ){
            if( $data['return_code'] == 'FAIL' ){
                return false;
            }
        }
        return $data;
    }

    /**
     * 接收通知成功后应答输出XML数据
     * @param string $xml
     */
    public function replyNotify(){
        $data['return_code'] = 'SUCCESS';
        $data['return_msg'] = 'OK';
        $xml = $this->data_to_xml( $data );
        echo $xml;
        die();
    }

    /**
     * 生成APP端支付参数
     * @param string $prepayid   预支付id
     */
    public function getAppPayParams( $prepayid ){
        $data['appid'] = $this->appid;
        $data['partnerid'] = $this->mch_id;
        $data['prepayid'] = $prepayid;
        $data['package'] = 'Sign=WXPay';
        $data['noncestr'] = $this->genRandomString();
        $data['timestamp'] = time();
        $data['sign'] = $this->MakeSign( $data );
        return $data;
    }
    /**
     * 生成签名
     *  @return string 签名
     */
    public function MakeSign( $params ){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$this->key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }

    /**
     * 输出xml字符
     * @param string $params  参数名称
     * return   string      返回组装的xml
     **/
    public function data_to_xml( $params ){
        if(!is_array($params)|| count($params) <= 0)
        {
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * return array
     */
    public function xml_to_data($xml){
        if(!$xml){
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 获取毫秒级别的时间戳
     */
    public static function getMillisecond(){
        //获取毫秒的时间戳
        $time = explode ( " ", microtime () );
        $time = $time[1] . ($time[0] * 1000);
        $time2 = explode( ".", $time );
        $time = $time2[0];
        return $time;
    }

    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     * @param string type $len 产生字符串的长度
     * @return string 随机字符串
     */
    public function genRandomString($len = 32) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 错误代码
     * @param int $code  服务器输出的错误代码
     * return string
     */
    public function error_code($code){
        $errList = array(
            'NOAUTH'                =>  '商户未开通此接口权限',
            'NOTENOUGH'             =>  '用户帐号余额不足',
            'ORDERNOTEXIST'         =>  '订单号不存在',
            'ORDERPAID'             =>  '商户订单已支付，无需重复操作',
            'ORDERCLOSED'           =>  '当前订单已关闭，无法支付',
            'SYSTEMERROR'           =>  '系统错误!系统超时',
            'APPID_NOT_EXIST'       =>  '参数中缺少APPID',
            'MCHID_NOT_EXIST'       =>  '参数中缺少MCHID',
            'APPID_MCHID_NOT_MATCH' =>  'appid和mch_id不匹配',
            'LACK_PARAMS'           =>  '缺少必要的请求参数',
            'OUT_TRADE_NO_USED'     =>  '同一笔交易不能多次提交',
            'SIGNERROR'             =>  '参数签名结果不正确',
            'XML_FORMAT_ERROR'      =>  'XML格式错误',
            'REQUIRE_POST_METHOD'   =>  '未使用post传递参数 ',
            'POST_DATA_EMPTY'       =>  'post数据不能为空',
            'NOT_UTF8'              =>  '未使用指定编码格式',
        );
        if( array_key_exists( $code , $errList ) ){
            return $errList[$code];
        }
    }

    //xml请求
    public function postXmlCurl($url,$xml,$second = 30){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置 header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post 提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行 curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            curl_close($ch);
            echo "curl 出错，错误码:$error"."<br>";
        }
    }


    //获取用户真实ip
    function getIp(){
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";
        return $ip;
    }

}
<?php

namespace app\admin\controller;

use think\App;
use think\Db;

//微信付款控制器
class WxTransfers extends Base
{
    private $config;

    function __construct(App $app = null)
    {
        $this->config=[
            //商户号
            'mchid'=>'',
            //应用ID,您的APPID
            'appid' => "",
            //异步通知地址*
            'notify_url' => "",
            //商户私钥*
            'apiv2_private_key' => "",
            //证书序列号
            'xlid'=>'',
            //证书路径
            'apiclient_cert'=>'apiclient_cert.pem',
            //证书路径
            'apiclient_key'=>'apiclient_key.pem',
        ];
        parent::__construct($app);
    }
    /**
     * 微信付款
     * @param $partner_trade_no string 订单号
     * @param $openid string 用户openid
     * @param $money string 金额元
     * @return mixed
     */
    public function transfers($partner_trade_no,$openid,$money) {
        $partner_trade_no=time().rand(1111,9999);//模拟数据订单号
        $openid='oJMnK5NFXXNhtPOHjNkOHmdf_VwQ'; //模拟数据openid
        $money = 1;//模拟数据金额

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $parameters = array(
            'mch_appid' => $this->config['appid'],
            'mchid' => $this->config['mchid'],
            'nonce_str' => $this->getNonceStr(),
            'partner_trade_no' => $partner_trade_no,
            'openid' => $openid,
            'check_name'=>'NO_CHECK',
            'amount' => $money*100, //付款金额单位为分
            'desc' => '洽购小程序转账',
        );
        //统一下单签名
        $parameters['sign'] = $this->getTransfersSign($parameters,$this->config['apiv2_private_key']);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url,true,$_SERVER['DOCUMENT_ROOT'].'/'.$this->config['apiclient_cert'],$_SERVER['DOCUMENT_ROOT'].'/'.$this->config['apiclient_key']));
        return $return;
        //返回值为一维数组 result_code 为 SUCCESS 时为成功为 FAIL 时为未确定状态，partner_trade_no 为付款时的订单号，err_code 为错误代码如返回SYSTEMERROR为未确定失败状态
        //成功返回示例 { ["return_code"]=> string(7) "SUCCESS" ["return_msg"]=> array(0) { } ["mch_appid"]=> string(18) "wx04bea948d09baa9e" ["mchid"]=> string(10) "1611598016" ["nonce_str"]=> string(31) "CQXKYRW50MZH2PI94D3GLA176OUFETS" ["result_code"]=> string(7) "SUCCESS" ["partner_trade_no"]=> string(14) "16455133585462" ["payment_no"]=> string(32) "10101321961412202224216776615256" ["payment_time"]=> string(19) "2022-02-22 15:02:40" }
    }

    //签名
    public function getTransfersSign($Obj,$key) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }

    ///作用：格式化参数，签名过程需要使用
    public function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    //数组转换成xml
    public function arrayToXml($arr) {
        $xml = "<root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</root>";
        return $xml;
    }

    //xml转换成数组
    public function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    //获取随机字符串
    public function getNonceStr(){
        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890";
        $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),32);
        return $name;
    }

    //post请求
    public static function postXmlCurl($xml, $url,$useCert=false,$f1="",$f2="", $second = 60){
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $f1);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $f2);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);

        //运行curl
        $data = curl_exec($ch);
        // echo json_encode($data);
        // exit;
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            exit("curl出错，错误码:$error");
        }
    }

}
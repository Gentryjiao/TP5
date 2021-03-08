<?php
namespace app\index\controller;
use think\Db;
use think\Request;
/*
    支付宝支付
    所需秘钥: 商品应用的appid
    商户私钥，您的原始格式RSA私钥
    支付宝公钥 https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    支付宝网关 https://openapi.alipay.com/gateway.do
    异步通知地址
    同步跳转
*/
class Alipay
{
    private $config = array (
        //应用ID,您的APPID。*
        'app_id' => "XXXXXX",
        //异步通知地址*
        'notify_url' => "xxxxxxxxxxxxxxxxxxxx",
        //同步跳转*
        'return_url' => "xxxxxxxxxxxxxxxxxxxx",
        //编码格式
        'charset' => "UTF-8",
        //签名方式
        'sign_type'=>"RSA2",
        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        //支付宝公钥*
        'alipay_public_key' => "xxxxxxxxxxxxxx",
        //商户私钥*
        'merchant_private_key' => "xxxxxxxxxxxxxxxxxx",
    );

    /*
     * 手机端支付宝支付
     * @param id 订单表id
     * */
    public function pay($id=0){
        if($id==0){
            return json(['status'=>'error','msg'=>'未知错误,请刷新重试']);
        }
        vendor('alipay.wappay.service.AlipayTradeService');
        vendor('alipay.wappay.buildermodel.AlipayTradeWapPayContentBuilder');

        //查询订单表
        $order=Db('order')->where('id',$id)->field('order,price')->find();
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order'];
        //订单名称，必填
        $subject = '收团宝会员';
        //付款金额，必填 单位 元
        $total_amount = 0.01;
        //商品描述，可空
        $body = '收团宝会员';
        //超时时间
        $timeout_express="1m";

        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $config=$this->config;
        $payResponse = new \AlipayTradeService($config);
        $result =$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        return ;
    }

    /*
     * pc端支付宝支付
     * @param id 订单表id
     * */
    public function pay_pc($id=0){
        if($id==0){
            return json(['status'=>'error','msg'=>'未知错误,请刷新重试']);
        }
        vendor('alipay_pc.config');
        vendor('alipay_pc.pagepay.service.AlipayTradeService');
        vendor('alipay_pc.pagepay.buildermodel.AlipayTradePagePayContentBuilder');

        //查询订单表
        $order=Db('viporder')->where('id',$id)->field('order,price')->find();
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order'];
        //订单名称，必填
        $subject = '收团宝会员';
        //付款金额，必填 单位 元
        $total_amount = 0.01;
        //商品描述，可空
        $body = '收团宝会员';

        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $config=$this->config;
        $aop = new \AlipayTradeService($config);
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);
    }


    /*
     * 异步通知页面
     * @post out_trade_no 订单号
     * @post trade_no 支付宝交易号
     * @post trade_status 交易状态
     * */
    public function notify(){
        $config=$this->config;
        vendor('alipay/wappay/service/AlipayTradeService');
        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        //验证成功
        if($result) {
            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                //查询订单表
                $order=Db('order')->where('order',$_POST['out_trade_no'])->find();
                if($order){
                    if($order['status']==0){
                        //支付成功处理逻辑*****
                        //支付成功处理逻辑*****
                        //支付成功处理逻辑*****
                    }else{
                        echo "fail";
                        die;
                    }
                }else{
                    echo "fail";
                    die;
                }
            }
        }else {
            echo "fail";
        }
    }

    /*
     * 支付成功跳转地址
     * @get out_trade_no 订单号
     * */
    public function results()
    {
        $orderdata = $_GET;
        //查询订单表
        $order=Db('order')->where('order',$orderdata['out_trade_no'])->field('status,order')->find();
        if($order){
            if($order['status']==1){
                //订单完成
                $this->redirect('index/usercenter/vip?zt=1');
            }else{
                $this->redirect('index/usercenter/vip?zt=2');
            }
        }else{
            $this->redirect('index/usercenter/vip?zt=2');
        }
    }

}



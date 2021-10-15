<?php
namespace app\index\controller;
use think\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use \GatewayWorker\Lib\Gateway;
//安装依赖包  composer require php-amqplib/php-amqplib
class Rabbitmq extends Controller
{
    public function md5jm($number){
        return md5($number);
    }
    //发送队列
    public function send($message='Hello World!'){
        //连接到服务器
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        //接着创建一个通道,声明一个队列
        $channel->queue_declare('hello', false, false, false, false);
        $msg = new AMQPMessage($message.date('Y-m-d H:i:s'));
        //发布消息放到hello队列中
        $channel->basic_publish($msg, '', 'hello');
        echo "Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
    }

    //接收队列
    public function receive(){
        //打开连接和通道，声明要消费的队列
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        //定义一个回调函数来处理消息
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
//        while (count($channel->callbacks)) {
            $channel->wait();
//        }
        $channel->close();
        $connection->close();
    }

    //渲染试图
    public function index(){
        $from_user_id=input('from_user_id');
        $to_user_id=input('to_user_id');
        $this->assign(['from_user_id'=>$from_user_id,'to_user_id'=>$to_user_id]);
        return $this->fetch();
    }
}
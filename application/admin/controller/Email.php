<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Loader;
class Email extends Controller
{
    /**
     * 邮件发送
     * @param  $to
     * @param string $subject 邮件标题
     * @param string $content 邮件内容(html模板渲染后的内容)
     * @throws Exception
     * @throws phpmailerException
     */
    public static function send_email($to='1715792133@qq.com',$subject='title',$content='content'){
        Loader::import('email.PHPMailerAutoload', EXTEND_PATH, '.php');
        Loader::import('email.class', EXTEND_PATH, '.phpmailer.php');
        Loader::import('email.class', EXTEND_PATH, '.smtp.php');
        $mail  = new \PHPMailer();
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];
        $config=Db::name('smtp_config')->where('id',1)->find();

        $mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->IsSMTP();                            // 设定使用SMTP服务
        $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl";                  // 启用SSL
        $mail->SMTPDebug = 2;
        $mail->Host       = $config['host'];       // SMTP 服务器
        $mail->Port       = 465;                    // SMTP服务器的端口号
        $mail->Username   = $config['username'];  // SMTP服务器用户名
        $mail->Password   = $config['password'];        // SMTP服务器密码
        $mail->SetFrom($config['username'], 'qq');    // 设置发件人地址和名称
        $mail->AddReplyTo($to,$config['username']);
        // 设置邮件回复人地址和名称
        $mail->Subject    = $subject;                     // 设置邮件标题
        $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
        // 可选项，向下兼容考虑
        $mail->MsgHTML($content);                         // 设置邮件内容
        $mail->AddAddress($to);
        //$mail->AddAttachment("images/phpmailer.gif"); // 附件
        if(!$mail->Send()) {
            echo "发送失败：" . $mail->ErrorInfo;
        } else {
            echo "恭喜，邮件发送成功！";
        }

    }

}
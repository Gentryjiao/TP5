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

    public function send_email($to,$subject,$content)
    {
        header("content-type:text/html;charset=utf-8");
        //收件人的邮箱 给谁发邮件
        $config=Db::name('smtp_config')->where('id',1)->find();
        vendor('PHPMailer.PHPMailer');
        $mail = new \PHPMailer();
        // 使用SMTP方式发送
        $mail->IsSMTP();
        // // 设置邮件的字符编码
        $mail->CharSet = 'UTF-8';
        // // 企业邮局域名
        $mail->Host = $config['host'];
        //---------qq邮箱需要的------//设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
        $mail->Port = 465;//---------qq邮箱需要的------
        // 启用SMTP验证功能
        $mail->SMTPAuth = true;
        //邮件发送人的用户名(请填写完整的email地址)
        $mail->Username = $config['username'];
        // 邮件发送人的 密码 （授权码）
        $mail->Password = $config['password'];  //修改为自己的授权码
        //邮件发送者email地址
        $mail->From = $config['username'];
        //发送邮件人的标题
        $mail->FromName = "虎虎保险Tigerless Health";
        //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//        $mail->AddAddress("$email_addr", substr($email_addr, 0, strpos($email_addr, '@')));
        $mail->AddAddress($to);
        //回复的地址
        $mail->AddReplyTo($config['username'], "");
        //$mail->AddAttachment("./mail.rar"); // 添加附件
        //set email format to HTML //是否使用HTML格式
        $mail->IsHTML(true);
        //邮件标题
//        $mail->Subject = 'subject of the mail';  //邮件标题
        $mail->Subject    = $subject;                     // 设置邮件标题
        //邮件内容
//        $mail->Body = "<p style='color:red'>" . 'verification code:' . $code . '</p >';   //内容
        $mail->MsgHTML($content);                         // 设置邮件内容

        //附加信息，可以省略
        $mail->AltBody = '';
        // 添加附件,并指定名称
        //$mail->AddAttachment('./error404.php', 'PHP file');
        if (!$mail->Send()) {
            return json(['code' => 0, 'msg' => 'failure notice', 'error' => $mail->ErrorInfo]); //发送失败
        } else {
            return json(['code' => 1, 'msg' => 'send successful']); //发送成功
        }
    }

}
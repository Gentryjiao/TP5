<?php

require 'PHPMailerAutoload.php';
require_once('class.phpmailer.php');
require_once("class.smtp.php");
$mail  = new PHPMailer();

$mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ]
];

$mail->CharSet    ="UTF-8";                 //�趨�ʼ����룬Ĭ��ISO-8859-1����������Ĵ����������Ϊ UTF-8
$mail->IsSMTP();                            // �趨ʹ��SMTP����
$mail->SMTPAuth   = true;                   // ���� SMTP ��֤����
$mail->SMTPSecure = "ssl";                  // ����SSL
$mail->SMTPDebug = 2;
$mail->Host       = "smtp.163.com";       // SMTP ������
$mail->Port       = 465;                    // SMTP�������Ķ˿ں�
$mail->Username   = "zsddzh@163.com";  // SMTP�������û���
$mail->Password   = "DMXMVRXWPADEDTVQ";        // SMTP����������
$mail->SetFrom('zsddzh@163.com', 'qq');    // ���÷����˵�ַ������
$mail->AddReplyTo("1715792133@qq.com","zsddzh@163.com");
                                            // �����ʼ��ظ��˵�ַ������
$mail->Subject    = 'ceshi';                     // �����ʼ�����
$mail->AltBody    = "Ϊ�˲鿴���ʼ������л���֧�� HTML ���ʼ��ͻ���";
                                            // ��ѡ����¼��ݿ���
$mail->MsgHTML('<html>helo</html>');                         // �����ʼ�����
$mail->AddAddress('1715792133@qq.com', "1715792133@qq.com");
//$mail->AddAttachment("images/phpmailer.gif"); // ����
if(!$mail->Send()) {
    echo "����ʧ�ܣ�" . $mail->ErrorInfo;
} else {
    echo "��ϲ���ʼ����ͳɹ���";
}

?>

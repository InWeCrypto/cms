<?php
require_once __DIR__ . '/../vendor/autoload.php';

use What00\SocketMail\Mail;

/** 
*服务器信息 
*/  
$MailServer = 'smtp.mxhichina.com';      //SMTP 服务器  
$MailPort   = '25';                  //SMTP服务器端口号 默认25  
$MailId     = 'register@inwecrypto.com';  //服务器邮箱帐号  
$MailPw     = 'inwecrypto.com‍+1O';                //服务器邮箱密码  
  
/** 
*客户端信息 
*/  
$Title      = 'TESTMAIL成功';        //邮件标题  
$Content    = '测试邮件内容';        //邮件内容  
$email      = 'what-00@qq.com';   //接收者邮箱  
$mail = new Mail($MailServer,$MailPort,true,$MailId,$MailPw);  
$mail->debug = false;  
if($mail->sendmail($email,$MailId, $Title, $Content, "HTML")){  
     echo '邮件发送成功';            //返回结果  
} else {  
     echo '邮件发送失败';            //$succeed = 0;  
}  
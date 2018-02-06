<?php

namespace What00\SocketMail;

use What00\SocketMail\Smtp;

class Mail
{
	var $debug;  
    var $host;  
    var $port;  
    var $auth;  
    var $user;  
    var $pass;  
  
    public function __construct($host = "", $port = 25,$auth = false,$user,$pass){  
        $this->host=$host;  
        $this->port=$port;  
        $this->auth=$auth;  
        $this->user=$user;  
        $this->pass=$pass;  
    }  
  
    public function sendmail($to,$from, $subject, $content, $T=0){  
        //$name, $email, $subject, $content, $type=0  
        $type=1;  
        $name=array($from);  
        $email=array($to);  
        $_CFG['smtp_host']= $this->host;  
        $_CFG['smtp_port']= $this->port;  
        $_CFG['smtp_user']= $this->user;  
        $_CFG['smtp_pass']= $this->pass;  
        $_CFG['name']= $from;  
        $_CFG['smtp_mail']= $from;  
  
        $subject = "=?UTF-8?B?".base64_encode($subject)."==?=";  
        $content = base64_encode($content);  
        $headers[] = "To:=?gbk?B?".base64_encode($name[0])."?= <$email[0]>";  
        $headers[] = "From:=?gbk?B?".base64_encode($_CFG['name'])."?= <$_CFG[smtp_mail]>";  
        $headers[] = "MIME-Version: Blueidea v1.0";  
        $headers[] = "X-Mailer: 9gongyu Mailer v1.0";  
        $headers[] = "Subject:$subject";  
        $headers[] = ($type == 0) ? "Content-Type: text/plain; charset=gbk; format=flowed" : "Content-Type: text/html; charset=utf-8; format=flowed";  
        $headers[] = "Content-Transfer-Encoding: base64";  
        $headers[] = "Content-Disposition: inline";  
        //    SMTP 服务器信息  
        $params['host'] = $_CFG['smtp_host'];  
        $params['port'] = $_CFG['smtp_port'];  
        $params['user'] = $_CFG['smtp_user'];  
        $params['pass'] = $_CFG['smtp_pass'];  
        if (empty($params['host']) || empty($params['port']))  
        {  
            // 如果没有设置主机和端口直接返回 false  
            return false;  
        }  
        else  
        {  
            //  发送邮件  
            $send_params['recipients']    = $email;  
            $send_params['headers']        = $headers;  
            $send_params['from']        = $_CFG['smtp_mail'];  
            $send_params['body']        = $content;  
  
            /*  用于测试信息 
            echo "<pre>"; 
            print_r($params); 
            print_r($send_params); 
            echo "</pre>"; 
            exit; 
            */  
            $smtp = new Smtp($params);
            if($smtp->connect() AND $smtp->send($send_params))  
            {  
                return TRUE;  
            }  
            else   
            {  
                return FALSE;  
            }   
        }  
    }  
}
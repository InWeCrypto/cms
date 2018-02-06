<?php

namespace App\Services;

require_once realpath(base_path('vendor/what-00/socket-send-mail/vendor/autoload.php'));

use What00\SocketMail\Mail;

class MailVerifyService
{
    private $verify_key = 'KEY:MAILVERIFY:';

    public function send($email = '')
    {

        try {
            // 判断验证码是否过期
            $this->isInvalid($email);
            /**
            *服务器信息
            */
            $MailServer  = config('mailverify.host');      //SMTP 服务器
            $MailPort    = config('mailverify.port');      //SMTP服务器端口号 默认25
            $MailId      = config('mailverify.username');  //服务器邮箱帐号
            $MailPw      = config('mailverify.password');  //服务器邮箱密码
            $expire_time = config('mailverify.expire_time');
            $verify_code = rand(100000, 999999);
            // dd($MailServer, $MailPort, $MailId, $MailPw, $expire_time, $verify_code);
            $mail = new Mail($MailServer,$MailPort,true,$MailId,$MailPw);

            $Title = trans_replace('custom.SEND_VERIFY_MAIL_TITLE', ['code'=>$verify_code]);
            $Content = trans_replace('custom.SEND_VERIFY_MAIL_CONTENT', ['code'=>$verify_code]);

            if(! $mail->sendmail($email,$MailId, $Title, $Content, "HTML")){
                throw new \Exception(trans('custom.SEND_VERIFY_ERROR'), SEND_VERIFY_ERROR);
            }
        } catch (\Exception $e){
            return fail($e->getMessage(), $e->getCode());
        }

        \Redis::set($this->verify_key . $email, $verify_code);
        \Redis::expire($this->verify_key . $email, $expire_time); // 设置过期时间

        return true;
    }
    public function isInvalid($verify_mail)
    {
        $diff = \Redis::ttl($this->verify_key . $verify_mail);
        if ($diff > 0) {
            throw new \Exception(trans_replace('custom.VERIFY_REPEAT', ['sec' => $diff ]), VERIFY_REPEAT);
        }
        return true;
    }

    public function check($verify_mail = '', $verify_code = '')
    {
        $code  = \Redis::get($this->verify_key . $verify_mail);
        if (!$code) {
            throw new \Exception(trans('custom.VERIFY_INVALID'), VERIFY_INVALID);
        }
        if ($code != $verify_code) {
            throw new \Exception(trans('custom.VERIFY_ERROR'), VERIFY_ERROR);
        }
        return true;
    }

    public function forgotPassword($email, $password)
    {
        /**
        *服务器信息
        */
        $MailServer  = config('mailverify.host');      //SMTP 服务器
        $MailPort    = config('mailverify.port');      //SMTP服务器端口号 默认25
        $MailId      = config('mailverify.username');  //服务器邮箱帐号
        $MailPw      = config('mailverify.password');  //服务器邮箱密码
        $expire_time = config('mailverify.expire_time');
        // dd($MailServer, $MailPort, $MailId, $MailPw, $expire_time, $verify_code);
        $mail = new Mail($MailServer,$MailPort,true,$MailId,$MailPw);

        $Title = trans_replace('custom.RESET_PASSWORD_MAIL_TITLE', ['password'=>$password]);
        $Content = trans_replace('custom.RESET_PASSWORD_MAIL_CONTENT', ['password'=>$password]);

        return $mail->sendmail($email,$MailId, $Title, $Content, "HTML");
    }
}

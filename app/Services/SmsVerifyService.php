<?php

namespace App\Services;

use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Illuminate\Http\Request;

class SmsVerifyService
{
    public function send($phone = '')
    {
        // 判断验证码是否过期
        $this->isInvalid($phone);

        $config = [
            'accessKeyId'     => env('ALI_KEY'),
            'accessKeySecret' => env('ALI_SECRETKEY')
        ];

        $sign_name     = config('smsverify.sign_name');
        $template_code = config('smsverify.template_code');
        $expire_time   = config('smsverify.expire_time');
        $verify_code   = rand(100000, 999999);

        $client  = new Client($config);
        $sendSms = new SendSms;

        $sendSms->setPhoneNumbers($phone);
        $sendSms->setSignName($sign_name);
        $sendSms->setTemplateCode($template_code);
        $sendSms->setTemplateParam(['code' => $verify_code]);
        $res = $client->execute($sendSms);

        if ($res->Code != 'OK') {
            throw new \Exception("发送验证码失败，重试！".$res->Message, SEND_VERIFY_ERROR);
        }

        \Redis::set(\SmsVerify::listKey . $phone, $verify_code);
        \Redis::expire(\SmsVerify::listKey . $phone, $expire_time); // 设置过期时间

        return true;
    }

    public function isInvalid($verify_phone)
    {
        $diff = \Redis::ttl(\SmsVerify::listKey . $verify_phone);
        if ($diff > 0) {
            throw new \Exception("请在 {$diff} 秒后重试！", VERIFY_REPEAT);
        }
        return true;
    }

    public function check($verify_phone = '', $verify_code = '')
    {
        $code  = \Redis::get(\SmsVerify::listKey . $verify_phone);
        if (!$code) {
            throw new \Exception("验证码错误,请重试！", VERIFY_INVALID);
        }
        if ($code != $verify_code) {
            throw new \Exception("验证码错误！", VERIFY_ERROR);
        }
        return true;
    }
}

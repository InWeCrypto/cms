<?php

namespace App\Services;

class EasemobApiService
{
    protected $root = 'http://a1.easemob.com/';

    private   $token_cache_key = 'KEY:SYSTEM:TOKEN:EASEMOB';

    // 获取token
    public function getToken()
    {
        if(\Redis::exists($this->token_cache_key)){
            return \Redis::get($this->token_cache_key);
        }
        $org = config('easemob.org');
        $app = config('easemob.app');
        $param = [
            'grant_type' => 'client_credentials',
            'client_id' => config('easemob.key'),
            'client_secret' => config('easemob.secret')
        ];

        $url = $this->root . $org . '/' . $app . '/token';

        $header = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];
        $res = sendCurl($url, $param, 'POST', $header);

        if(empty($res['access_token'])){
            throw new \Exception(trans('custom.GET_API_TOKEN_FAIL'), FAIL);
        }

        $token  = $res['access_token'];
        $expire = $res['expires_in'] - 100;

        \Redis::set($this->token_cache_key, $token);
        \Redis::expire($this->token_cache_key, $expire);

        return $token;
    }

    public function sendCurl($url, $body=[], $type = 'GET')
    {
        $org = config('easemob.org');
        $app = config('easemob.app');
        $url = $this->root . $org . '/' . $app . '/' . $url;
        $header = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getToken(),
        ];
        return sendCurl($url, $body, $type, $header);
    }

    public function test()
    {
        dd($this->token);
    }
}

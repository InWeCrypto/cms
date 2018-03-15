<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class BaseSource
{
    // 列表数据列表链接
    protected $uri = '';
    // 交易所网站前缀
    protected $uri_prefix = '';
    // 下一页
    protected $next_page_uri = '';
    // 上一页
    protected $prev_page_uri = '';
    // 语言
    protected $lang = '';
    // 交易所来源名称
    protected $ex_notice_name = '';
    // 请求数据cookie
    protected $cookie = '';
    // 使用代理访问

    public function getHtml($uri)
    {
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);


        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)');
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');


        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        if($this->cookie){
            curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        }

        // if(env('CURL_PROXY_DEFAULT', false)){
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);

            curl_setopt($ch, CURLOPT_PROXY, env('CURL_PROXYADDR'));
            curl_setopt($ch, CURLOPT_PROXYPORT, env('CURL_PROXYPORT'));
        // }

        $res = curl_exec($ch);

        $error_msg = curl_error($ch);

        if($error_msg){
            dd($error_msg);
        }

        curl_close($ch);
        // $res = $this->getTestHtml();
        return $res;
    }

    public function setArticleCache($uri, $uri_md5, $source, $lang, $article_title, $article_content, $article_date)
    {
        $data = compact('uri', 'uri_md5', 'source', 'lang', 'article_title', 'article_content', 'article_date');
        $info = \App\Model\ExchangeNoticeTemp::create($data);
        return $info->toArray();
    }

    public function getArticleCache($uri_md5, $lang = 'zh')
    {
        $info = \App\Model\ExchangeNoticeTemp::where('uri_md5', $uri_md5)->where('lang', $lang)->first();
        return $info ? $info->toArray() : null;
    }

}

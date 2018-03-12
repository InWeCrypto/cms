<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class BinanceNoticeSource extends BaseSource
{
    protected $uri = '';

    protected $uri_prefix = 'https://support.binance.com';

    protected $lang_uri = [
        'en' => 'https://support.binance.com/hc/en-us/sections/115000202591',
        'zh' => 'https://support.binance.com/hc/zh-cn/sections/115000202591',
    ];

    protected $lang = '';

    protected $ex_notice_name = '';
    // 下一页
    protected $next_page_uri = '';
    // 上一页
    protected $prev_page_uri = '';

    public function lang($val)
    {
        $this->lang = $val;
        $this->uri = $this->lang_uri[$val];
        return $this;
    }

    public function exNoticeName($val)
    {
        $this->ex_notice_name = $val;
        return $this;
    }

    public function getData($uri='')
    {
        $data = [];
        $uri = $uri ?: $this->uri;
        $article_list = $this->getArticleList($uri);
        foreach($article_list as $li){
            $data[] = $this->getArticleContent($li);
        }
        $prev_page = base64_encode($this->prev_page_uri);
        $next_page = base64_encode($this->next_page_uri);
        return compact('data','prev_page','next_page');
    }

    public function getArticleList($uri)
    {
        $uri_md5 = md5($uri);
        // 判断 数据库有没有该文章
        if($article = $this->getArticleCache($uri_md5)){
            return $article;
        }
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);

        $title = '';
        $content = '';
        $article_title = html_entity_decode($Html->find('title', 0)->innertext ?: '');
        $article_content = $Html->find('div.article-body', 0)->innertext ?: '';
        $article_date = $Html->find('li.meta-data', 0)->children(0)->datetime;
        $lang = $this->lang;
        $source = $this->ex_notice_name;
        return $this->setArticleCache($uri, $uri_md5, $source, $lang, $article_title, $article_content, $article_date);
    }

    public function getArticleContent($uri)
    {
        $title = '';
        $content = '';
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);
        $title = $Html->find('title', 0)->innertext ?: '';
        $content = $Html->find('div.article-body', 0)->innertext ?: '';
        $time = $Html->find('li.meta-data', 0)->children(0)->datetime;
        $lang = $this->lang;
        $ex_notice_name = $this->ex_notice_name;
        return compact('title','content','time','uri','lang','ex_notice_name');
    }

    public function setArticleCache($uri, $uri_md5, $source, $lang, $article_title, $article_content, $article_date)
    {
        $data = compact('uri', 'uri_md5', 'source', 'lang', 'article_title', 'article_content', 'article_date');
        $info = \App\Model\ExchangeNoticeTemp::create($data);
        return $info->toArray();
    }

    public function getArticleCache($uri_md5)
    {
        $info = \App\Model\ExchangeNoticeTemp::where('uri_md5', $uri_md5)->first();
        return $info ? $info->toArray() : null;
    }
}

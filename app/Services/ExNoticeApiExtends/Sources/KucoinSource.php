<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class KucoinSource extends BaseSource
{
    protected $uri = '';

    protected $uri_prefix = '';

    protected $lang_uri = [
        'en' => 'https://news.kucoin.com/en/category/announcements/',
        'zh' => 'https://news.kucoin.com/category/%E5%85%AC%E5%91%8A/',
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

    public function getData($uri = '')
    {
        $data = [];
        $uri = $uri ?: $this->uri;
        $article_list = $this->getArticleList($uri);
        foreach($article_list as $li){
            $data[] = $this->getArticleContent($li);
        }
        $prev_page = $this->prev_page_uri;
        $next_page = $this->next_page_uri;
        return compact('data','page');
    }

    public function getArticleList($uri)
    {
        $return = [];
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);
        $Dom = $Html->find('h2.post-title a');
        // 获取下一页链接
        if($nextPage = $Html->find('a.next', 0)){
            $this->next_page_uri = $this->uri_prefix . $nextPage->href;
        }
        // 获取上一页链接
        if($prevPage = $Html->find('a.prev', 0)){
            $this->prev_page_uri = $this->uri_prefix . $prevPage->href;
        }
        foreach($Dom as $li){
            if(empty($li->attr['href'])){
                continue;
            }
            $return[] = $this->uri_prefix . $li->attr['href'];
        }
        return $return;
    }

    public function getArticleContent($uri)
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
        $article_content = $Html->find('div.post-content', 0)->innertext ?: '';
        // 替换底部
        $article_content = $this->getContent($article_content);
        $article_date = $Html->find('span.post-date', 0)->innertext ?: '';
        $article_date = $this->getDate($article_date);
        $lang = $this->lang;
        $source = $this->ex_notice_name;

        return $this->setArticleCache($uri, $uri_md5, $source, $lang, $article_title, $article_content, $article_date);
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

    public function getContent($txt)
    {
        $pattern = '/(<p[^>]*class.*>.*<\/p>)/isU';
        return preg_replace($pattern, '', $txt);
    }

    public function getDate($txt)
    {
        $pattern = '/(?<date>\d{1,4}-\d{1,2}-\d{1,2})/is';
        preg_match($pattern, $txt, $matchs);
        return $matchs['date'];
    }

}

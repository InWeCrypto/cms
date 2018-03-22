<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class GateIoSource extends BaseSource
{
    protected $uri = '';

    protected $uri_prefix = 'https://gate.io';

    protected $lang_uri = [
        'en' => 'https://gate.io/articlelist/ann',
        'zh' => 'https://gate.io/articlelist/ann',
    ];

    protected $lang = '';

    protected $ex_notice_name = '';
    // 下一页
    protected $next_page_uri = '';
    // 上一页
    protected $prev_page_uri = '';

    public function lang($val)
    {
        $langs = [
            'en' => 'en',
            'zh' => 'cn'
        ];
        $this->lang = $val;
        $this->uri = $this->lang_uri[$val];
        // 设置cookie
        $this->cookie = 'lang='.$langs[$val];
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
        return compact('data','prev_page', 'next_page');
    }

    public function getArticleList($uri)
    {
        $return = [];
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);
        $Dom = $Html->find('div.latnewslist a');
        // 获取下一页链接
        $next_page_uri_key = $this->lang == 'zh' ? '下一页' : 'Next';
        if($nextPage = $Html->find('div#paginationtech a[title='.$next_page_uri_key.']', 0)){
            $this->next_page_uri = $this->uri_prefix . $nextPage->href;
        }
        // 获取上一页链接
        $prev_page_uri_key = $this->lang == 'zh' ? '上一页' : 'Previous';
        if($prevPage = $Html->find('div#paginationtech a[title='.$prev_page_uri_key.']', 0)){
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
        if($article = $this->getArticleCache($uri_md5, $this->lang)){
            return $article;
        }
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);

        $title = '';
        $content = '';
        $article_title = html_entity_decode($Html->find('div.dtl-title h2', 0)->innertext ?: '');
        $article_content = $Html->find('div.dtl-content', 0)->innertext ?: '';
        // 替换底部
        $article_content = $this->getContent($article_content);
        $article_date = $Html->find('div.new-dtl-info span', 0)->innertext ?: '';
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

    public function getContent($txt)
    {
        $pattern = '/<ul class\="prenext">.*<\/div>/is';
        return preg_replace($pattern, '', $txt);
    }

}

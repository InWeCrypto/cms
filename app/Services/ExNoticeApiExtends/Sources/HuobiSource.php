<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class HuobiSource extends BaseSource
{
    protected $uri = '';

    protected $uri_prefix = 'https://www.huobi.com/p/api/contents/pro/notice/';

    protected $lang_uri = [
        'en' => 'https://www.huobi.com/p/api/contents/pro/list_notice?limit=10&language=en-us',
        'zh' => 'https://www.huobi.com/p/api/contents/pro/list_notice?limit=10&language=zh-cn',
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

        $list = json_decode($html_txt, true);

        $items = $list['data']['items'];
        foreach($items as $item){
            $return[] = $this->uri_prefix . $item['id'];
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

    public function getArticleCache($uri_md5)
    {
        $info = \App\Model\ExchangeNoticeTemp::where('uri_md5', $uri_md5)->first();
        return $info ? $info->toArray() : null;
    }

    public function getContent($txt)
    {
        $pattern = '/<ul class\="prenext">.*<\/div>/is';
        return preg_replace($pattern, '', $txt);
    }

}

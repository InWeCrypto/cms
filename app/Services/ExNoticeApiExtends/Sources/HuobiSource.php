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

        $prev_page = base64_encode($this->prev_page_uri);
        $next_page = base64_encode($this->next_page_uri);
        return compact('data','prev_page', 'next_page');
    }

    public function getArticleList($uri)
    {
        $return = [];
        $html_txt = $this->getHtml($uri);

        $list = json_decode($html_txt, true);
        $current_page = $list['data']['currentPage'];
        if($current_page <= 1){
            $this->prev_page_uri = '';
        }else{
            $this->prev_page_uri = $uri . '&page=' . ($current_page - 1);
        }
        if($current_page >= $list['data']['pages']){
            $this->next_page_uri = '';
        }else{
            $this->next_page_uri = $uri . '&page=' . ($current_page + 1);
        }
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
        if($article = $this->getArticleCache($uri_md5, $this->lang)){
            return $article;
        }

        $html_txt = $this->getHtml($uri);
        $data = json_decode($html_txt, true);

        $article_title = $data['data']['title'];
        $article_content = $data['data']['content'];
        $article_date = date('Y-m-d H:i:s',$data['data']['created']);
        $lang = $this->lang;
        $source = $this->ex_notice_name;
        return $this->setArticleCache($uri, $uri_md5, $source, $lang, $article_title, $article_content, $article_date);
    }


}

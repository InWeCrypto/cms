<?php

namespace App\Services\ExNoticeApiExtends\Sources;

class OkexNoticeSource extends BaseSource
{
    protected $uri = '';

    protected $uri_prefix = 'https://support.okex.com';

    protected $lang_uri = [
        'en' => 'https://support.okex.com/hc/en-us/sections/360000030652',
        'zh' => 'https://support.okex.com/hc/zh-cn/sections/360000030652',
    ];

    protected $lang = '';

    protected $ex_notice_name = '';

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
        $return = [];
        $uri = $uri ?: $this->uri;
        $article_list = $this->getArticleList($uri);
        foreach($article_list as $li){
            $return[] = $this->getArticleContent($li);
        }
        return $return;
    }

    public function getArticleList($uri)
    {
        $return = [];
        $html_txt = $this->getHtml($uri);
        $Html = str_get_html($html_txt);
        $Dom = $Html->find('a.article-list-link');
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
}

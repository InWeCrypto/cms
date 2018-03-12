<?php

namespace App\Services;

use App\Services\ExNoticeApiExtends\DataSource as ExNoticeApiDataSource;

class ExNoticeApiService
{
    /**
    * @param string $ex 交易所标识
    * @param string $lang 抓取语言
    * @param string $url 交易所文章上下页链接
    *
    */
    public function getData($ex, $lang, $url = '')
    {
        $ex = strtolower($ex);
        $Source = ExNoticeApiDataSource::getSource($ex);
        $source_name = $this->getExNoticeName($ex, $lang);
        $Obj = new $Source();

        // $Obj
        $data = $Obj->lang($lang)->exNoticeName($source_name)->getData($url);

        dd($data);
    }

    private function getExNoticeName($ex, $lang)
    {
        $return = ExNoticeApiDataSource::getSourceName($ex, $lang);

        return $return;
    }

}

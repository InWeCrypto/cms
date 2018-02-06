<?php

namespace App\Services;

class IcoDataApiService
{
    // 获取k线图
    public function klines($ico_type, $interval= '5m', $currency = 'usdt', $end_time = 0) {
        $end_time = strtotime(gmdate('Y-m-d H:i:s')).'000';
        $return   = [];
        $interval = $interval;
        $symbol   = strtoupper($ico_type.$currency);
        $url      = 'https://www.binance.com/api/v1/klines?symbol=' . $symbol . '&interval=' . $interval;
        if($end_time){
            $url .= '&endTime='. $end_time;
        }
        $list_key = 'LIST:KLINES:'. strtoupper($interval) . ':' . $symbol;
        if(\Redis::exists($list_key)){
            // 获取缓存
            $temp = \Redis::lrange($list_key, 0, -1);
            while(list($k, $v) = each($temp)){
                $return[] = json_decode($v, true);
            }
            return $return;
        }

        try{
            $data = sendCurl($url);
        } catch (\Exception $e) {
            \Log::info('获取['. $url .']API数据失败,'.$e->getMessage());
            \Redis::rpush($list_key, '{}');
            \Redis::expire($list_key, 60);
            return [];
        }

        $expire_time = end($data)[6];
        $expire_time = intval($expire_time/1000 + 8*60*60); // 转换redis过期时间
        reset($data);
        while(list($k, $v) = each($data)){
            $tmp = [
                'time' => $v[0],                    // 开始时间
                'end_time' => $v[6],                // 结束时间
                'min_price' => $v[3],               // 最低价
                'max_price' => $v[2],               // 最高价
                'opened_price' => $v[1],            // 开盘价
                'closed_price' => $v[4],            // 收盘价
                'volume' => $v[5]                   // 交易量
            ];
            // // 只缓存最新的数据
            if($expire_time > time()){
                \Redis::rpush($list_key, json_encode($tmp));
            }
            $return[] = $tmp;
        }
        if($expire_time > time()){
            if(strcasecmp($interval, '1d') == 0 || strcasecmp($interval, '1w') == 0){
                \Redis::expire($list_key, 60 * 2 ); // 5分钟
                \Log::info('写入redis日志, KEY:' . $list_key . ',过期时间:' . (60 * 2));
            }else{
                \Redis::expireAt($list_key, $expire_time);
                \Log::info('写入redis日志, KEY:' . $list_key . ',过期时间:' . $expire_time);
            }
        }

        return $return;
    }

    // 获取实时介个
    public function timePrice24H($ico, $currency = 'usdt', $interval = '5m')
    {
        if(empty($ico) || empty($currency)) return false;

        $cache_key    = strtoupper('KEY:TIME_PRICE_24H:' . $ico . '_' . $currency);
        $cache_expire = 60 * 5;

        if(\Redis::exists($cache_key) && $cache = \Redis::get($cache_key)){
            return json_decode($cache, true);
        }

        $ratio  = 1;
        // 如果 是与usdt做交易对,则获取先获取btc价格
        if($currency == 'usdt' && strtolower($ico) != 'btc'){
            $btc_usdt_temp = \IcoDataApi::klines('btc', $interval, 'usdt');
            $ratio         = end($btc_usdt_temp)['closed_price'] ?: 1;
            $currency      = 'btc';
            unset($btc_usdt_temp);
        }

        $ico_temp  = \IcoDataApi::klines($ico, $interval, $currency);
        $price_24h = $this->get24hPrice($ico_temp);
        if(empty($price_24h)){
            \Log::info('获取计算 '. $ico .' 24H数据失败!'.json_encode(compact('ico','interval','currency')));
            \Redis::set($cache_key, '{}');
            \Redis::expire($cache_key, $cache_expire);
            return [];
        }
        unset($ico_temp);
        $change_24h = 0;
        if(!empty($price_24h['closed_price']) && !empty($price_24h['opened_price'])){
            $change_24h = bcmul(bcdiv(bcsub($price_24h['closed_price'], $price_24h['opened_price'], 10),$price_24h['opened_price'],10), 100, 10);
        }else{
            \Log::info('计算 '. $ico .'的24H浮动错误!');
        }

        $price_24h['change']   = sprintf("%.2f", $change_24h);
        $price_24h['opened_price'] = bcmul($price_24h['opened_price'], $ratio, 10);
        $price_24h['closed_price'] = bcmul($price_24h['closed_price'], $ratio, 10);
        $price_24h['max_price']    = bcmul($price_24h['max_price'], $ratio, 10);
        $price_24h['min_price']    = bcmul($price_24h['min_price'], $ratio, 10);
        $price_24h['volume']       = bcmul($price_24h['volume'], $ratio, 10);

        \Redis::set($cache_key, json_encode($price_24h));
        \Redis::expire($cache_key, $cache_expire);
        return $price_24h;
    }

    // 获取交易市场
    public function markets($ico_type)
    {
        $return  = [];
        $expire  = 60 * 60 * 12; // 过期时间12 小时
        $listKey = 'LIST:MARKETS:' . strtoupper($ico_type);
        $hashKey = 'HASH:MARKETS:' . strtoupper($ico_type) . ':';
        if(! empty(\Redis::exists($listKey))){
            $list = \Redis::lrange($listKey,0,-1);
            foreach($list as $li){
                $return[] = \Redis::hGetall($hashKey . $li);
            }
        }else{
            // 远程获取数据并缓存
            $return = $this->getMarketsByHtml($ico_type, $listKey, $hashKey, $expire);
        }
        return $return;
    }

    // 爬虫,获取 交易市场数据
    private function getMarketsByHtml($ico_type, $listKey, $hashKey, $expire = 60)
    {
        $return = [];
        $fields = [
            0 => 'sort',
            1 => 'source',
            2 => 'pair',
            3 => 'volum_24',
            4 => 'pairce',
            5 => 'volum_percent',
            6 => 'update'
        ];
        $url = 'https://coinmarketcap.com/currencies/'. $ico_type.'/';
        $contents = $this->getHttpsContent($url);
        $contents = str_replace(PHP_EOL, '', $contents);
        $get_tbody_pattern = '/<tbody[^>]*?>(?<tbody>.*)<\/tbody>/isU';
        preg_match($get_tbody_pattern, $contents, $tbody_data);
        if(!isset($tbody_data['tbody'])){
            return [];
        }
        if($tbody = $tbody_data['tbody']){
            $get_tr_pattern = '/<tr[^>]*?>(?<tr>.*)<\/tr>/isU';
            preg_match_all($get_tr_pattern, $tbody, $tr_data);
            if($tr = $tr_data['tr']){
                $get_td_pattern = '/<td[^>]*?>(?<td>.*)<\/td>/isU';
                foreach($tr as $_tr){
                    preg_match_all($get_td_pattern, $_tr, $td_data);
                    if($td = $td_data['td']){
                        $tmp = [];
                        $get_text_pattern = '/(?<=>)(?<text>[^<]*)(?=<)/isU';
                        foreach($td as $_td){
                            preg_match($get_text_pattern, $_td, $text_data);
                            $tmp[] = trim($text_data['text'] ?? $_td);
                        }
                        $cache    = array_combine($fields, $tmp);
                        \Redis::rpush($listKey, $cache['sort']);
                        \Redis::expire($listKey, $expire); // 设置过期时间

                        \Redis::hMset($hashKey.$cache['sort'], $cache);
                        \Redis::expire($hashKey.$cache['sort'], $expire); // 设置过期时间
                        $return[] = $cache;
                    }
                }
            }
        }
        return $return;
    }

    // 获取24小时内的开始价格和结束价格
    public function get24hPrice($data){
        $return       = [
            'opened_time' => 0,
            'closed_time' => 0,
            'opened_price' => 0,
            'closed_price' => 0,
            'max_price' => 0,
            'min_price' => 0,
            'volume' => 0
        ];
        $last_data    = end($data);
        if(empty($last_data)) return $return;
        $time         = $last_data['time']; // data数组内最后的时间
        $valid_time   = $time - 24 * 60 * 60 * 1000; // 24h之前的时间
        $closed_price = $last_data['closed_price'];  // data最后的价格
        $opened_price = 0;
        $first_time   = 0;
        $max_price    = $last_data['max_price'];
        $min_price    = $last_data['min_price'];
        $volume       = $last_data['volume'];
        foreach(array_reverse($data) as $v){
            if(!empty($v['time']) && $v['time'] <= $valid_time){
                $first_time   = $v['time'];
                $opened_price = $v['opened_price'];
                !empty($v['max_price']) && $v['max_price'] > $max_price && $max_price = $v['max_price'];
                !empty($v['min_price']) && $v['min_price'] < $min_price && $min_price = $v['min_price'];
                $volume = bcadd($volume, $v['volume'], 10);
                break;
            }
        }

        unset($data);

        $return['opened_time'] = $first_time;
        $return['closed_time'] = $time;
        $return['opened_price'] = $opened_price;
        $return['closed_price'] = $closed_price;
        $return['max_price'] = $max_price;
        $return['min_price'] = $min_price;
        $return['volume'] = $volume;

        return $return;
    }

    // 获取市值
    public function getMarketRank($ico_type)
    {
        $return = [];
        $cache_time = 60 * 5;
        $cache_key  = strtoupper('key:market_rank:'.$ico_type);
        $url = 'https://api.coinmarketcap.com/v1/ticker/'.$ico_type.'/';
        try {
            if(\Redis::exists($cache_key)){
                return json_decode(\Redis::get($cache_key), true);
            }
            $res = sendCurl($url);
            if(empty($res[0])){
                $return = [];
                $cache_time /= 2;
            }
            $return = $res[0];
            \Redis::set($cache_key, json_encode($return));
            \Redis::expire($cache_key, $cache_time);
        } catch (\Exception $e) {
        }
        return $return;
    }

    // 美元 人民币 汇率
    public function getUsdToCny()
    {
        $key      = 'KEY:USD:CNY';
        $expire   = 60 * 60 * 1;
        $return   = 0;
        if(! $return = \Redis::get($key)){
            $contents = $this->getHttpsContent('https://www.binance.com/exchange/public/cnyusd');
            $tmp      = json_decode($contents, true);
            if($tmp['success']){
                $return = $tmp['rate'];
            }
            \Redis::set($key, $return);
            \Redis::expire($key, $expire);
        }
        return $return;
    }

    // curl https数据
    private function getHttpsContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

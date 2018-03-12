<?php

namespace App\Services\ExNoticeApiExtends;

class DataSource
{
    // 用户选择爬虫列表
    private static $list = [
        'binance_115000202591' => '币安 - 最新公告',
        'binance_115000106672' => '币安 - 新币上线',
        'gate_io' => 'Gate.io',
        'kucoin' => 'KuCoin',
        'okex_360000030652' => 'OKEX - 最新公告',
        'okex_3115000447632' => 'OKEX - 新币上线',
        'huobi' => '火币',
    ];
    // 数据来源
    private static $sources = [
        'binance_115000106672' => Sources\BinanceCurrencySource::class,
        'binance_115000202591' => Sources\BinanceNoticeSource::class,
        'gate_io' => Sources\GateIoSource::class,
        'kucoin' => Sources\KucoinSource::class,
        'okex_360000030652' => Sources\OkexNoticeSource::class,
        'okex_3115000447632' => Sources\OkexCurrencySource::class,
        'huobi' => Sources\HuobiSource::class,
    ];
    // 数据来源名称
    private static $sources_name = [
        'binance_115000106672' => [
            'zh' => '币安',
            'en' => 'Binance'
        ],
        'binance_115000202591' => [
            'zh' => '币安',
            'en' => 'Binance'
        ],
        'gate_io' => [
            'zh' => 'Gate.io',
            'en' => 'Gate.io'
        ],
        'kucoin' => [
            'zh' => '酷币',
            'en' => 'KuCoin'
        ],
        'okex_360000030652' => [
            'zh' => 'OKEX',
            'en' => 'OKEX'
        ],
        'okex_3115000447632' => [
            'zh' => 'OKEX',
            'en' => 'OKEX'
        ],
        'huobi' => [
            'zh' => '火币',
            'en' => 'Huobi'
        ],
    ];

    public static function getSource($ex)
    {
        return self::$sources[$ex];
    }

    public static function getSourceList()
    {
        return self::$list;
    }

    public static function getSourceName($ex, $lang='zh')
    {
        return self::$sources_name[$ex][$lang];
    }
}

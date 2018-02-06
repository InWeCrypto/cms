<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EasemobMsg extends Facade
{
    public static $target_types = [
        'user' => 'users',
        'group' => 'chatgroups',
        'room' => 'chatrooms',
    ];

    const SYS_MSG = 0;
    const SYS_MSG_INWEHOT = 1;
    const SYS_MSG_TRADING = 2;
    const SYS_MSG_EXCHANGENOTICE = 3;
    const SYS_MSG_CANDYBOW = 4;
    const SYS_MSG_ORDER = 5;

    public static $sys_msg_typs = [
        self::SYS_MSG => '通知',
        self::SYS_MSG_INWEHOT => 'InWe热点',
        self::SYS_MSG_TRADING => 'Trading View',
        self::SYS_MSG_EXCHANGENOTICE => '交易所公告',
        self::SYS_MSG_CANDYBOW => '空投',
        self::SYS_MSG_ORDER => '交易提醒',
    ];

    public static $user_msg = [ // 一对一消息
        self::SYS_MSG,
        self::SYS_MSG_ORDER,
    ];

    public static $group_msg = [ // 组消息
        self::SYS_MSG_INWEHOT,
        self::SYS_MSG_TRADING,
        self::SYS_MSG_EXCHANGENOTICE,
        self::SYS_MSG_CANDYBOW,
    ];

    protected static function getFacadeAccessor()
	{
		return 'easemob_msg_api';
	}
}

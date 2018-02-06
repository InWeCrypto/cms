<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use \EasemobGroup as EGroup;

/**
 * Class Article
 * @package App\Model
 */
class EasemobGroup extends Model
{
	protected $table = 'easemob_groups';

    protected $fillable = [
        'type',
        'group_id',
    ];

    const SYS_MSG = 0;
    const SYS_MSG_INWEHOT = 1;
    const SYS_MSG_TRADING = 2;
    const SYS_MSG_EXCHANGENOTICE = 3;
    const SYS_MSG_CANDYBOW = 4;
    const SYS_MSG_ORDER = 5;

    public static $types = [
        self::SYS_MSG => 'SYS_MSG',
        self::SYS_MSG_INWEHOT => 'SYS_MSG_INWEHOT',
        self::SYS_MSG_TRADING => 'SYS_MSG_TRADING',
        self::SYS_MSG_EXCHANGENOTICE => 'SYS_MSG_EXCHANGENOTICE',
        self::SYS_MSG_CANDYBOW => 'SYS_MSG_CANDYBOW',
        self::SYS_MSG_ORDER => 'SYS_MSG_ORDER',
    ];

    public static $user_types = [ // 一对一消息
        self::SYS_MSG,
        self::SYS_MSG_ORDER,
    ];

    public static $group_types = [ // 组消息
        self::SYS_MSG_INWEHOT,
        self::SYS_MSG_TRADING,
        self::SYS_MSG_EXCHANGENOTICE,
        self::SYS_MSG_CANDYBOW,
    ];


}

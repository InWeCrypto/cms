<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class IcoAssessLevelTag extends Model
{
	protected $table   = 'ico_assess_level_tags';
	protected $guarded = [];

    public const RECOMMEND = 1;
    public const RISK = 2;

    public static $type = [
        self::RECOMMEND => '推荐标签',
        self::RISK => '风险标签'
    ];

}

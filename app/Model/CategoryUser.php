<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryUser extends Model
{
	protected $table = 'category_user_relations';
	protected $fillable = [
        'category_id',
        'user_id',
        'is_favorite',
        'is_market_follow',
        'market_hige',
        'market_lost',
        'score',
    ];
    protected $casts = [
        'is_favorite' => 'boolean',
        'is_market_follow' => 'boolean',
    ];

}

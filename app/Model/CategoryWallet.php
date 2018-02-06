<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryWallet extends Model
{
	protected $table = 'category_wallets';

    protected $fillable = [
        'name',
        'desc',
        'url',
        'sort',
        'enable',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('sort','DESC')
			               ->orderBy('id','DESC');
        });
    }
}

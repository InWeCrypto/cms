<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ExchangeNotice extends Model
{
	protected $table = 'exchange_notices';

    protected $fillable = [
        'source_name',
        'source_url',
        'content',
        'url',
        'is_hot',
        'is_top',
        'is_scroll',
        'enable',
        'lang',
    ];

    protected $hidden = [
        'content'
    ];

    protected $casts = [
        'is_scroll' => 'boolean',
        'is_top' => 'boolean',
        'is_hot' => 'boolean',
        'enable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('is_top','DESC')
			               ->orderBy('is_hot','DESC')
			               ->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });

    }
}

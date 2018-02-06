<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Ad extends Model
{
	protected $table = 'ads';

    protected $fillable = [
        'name',
        'desc',
        'img',
        'url',
        'sort',
        'enable',
        'lang'
    ];

    protected $casts = [
        'enable' => 'boolean'
    ];

    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });
    }
}

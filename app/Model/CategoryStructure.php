<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryStructure extends Model
{
	protected $table = 'category_structures';

    protected $fillable = [
        'percentage',
        'color_value',
        'desc',
        'lang',
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

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryIndustry extends Model
{
	protected $table = 'category_industrys';

    protected $fillable = [
        'category_id',
        'name',
        'desc',
        'lang',
    ];

    protected static function boot()
    {

        parent::boot();

        // static::addGlobalScope('sort', function(Builder $builder) {
		// 	return $builder->orderBy('sort','DESC')
		// 	               ->orderBy('updated_at','DESC')
		// 	               ->orderBy('id','DESC');
        // });

    }
}

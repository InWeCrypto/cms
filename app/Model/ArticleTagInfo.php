<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ArticleTagInfo extends Model
{
	protected $table = 'article_tags';

    protected $fillable = [
        'name',
        'desc',
        'lang',
        'enable',
        'sort'
    ];

    protected $casts = [
        'enable' => 'boolean'
    ];

    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('sort','DESC')
			               ->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });
    }
}

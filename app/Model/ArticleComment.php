<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ArticleComment extends Model
{
	protected $table = 'article_comments';

    protected $casts = [
        'enable' => 'boolean'
    ];

    protected $hidden = [
        'id',
        'updated_at',
        'enable',
        'user_id',
        'ip'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('created_at','DESC')
			               ->orderBy('id','DESC');
        });


    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id','user_id');
    }
}

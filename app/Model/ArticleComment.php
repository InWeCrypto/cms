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

    protected $fillable = [
        'content'
    ];

    protected $casts = [
        'enable' => 'boolean'
    ];

    protected $hidden = [
        // 'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('created_at','DESC')
			               ->orderBy('id','DESC');
        });


    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id')->select('id','title');
    }

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id','user_id');
    }
}

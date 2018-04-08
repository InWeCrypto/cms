<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryUserComment extends Model
{
	protected $table = 'category_user_comments';

    protected $fillable = [
        'category_id',
        'category_user_id',
        'content',
        'user_id',
    ];

    protected $hidden = [
        'ip',
        'enable',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id')->select(['id','name','img','email']);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['id','name','long_name','unit','type']);
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //
    //     static::addGlobalScope('sort', function(Builder $builder) {
	// 		return $builder->orderBy('id','DESC');
    //     });
    // }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ArticleUser extends Model
{
	protected $table = 'article_user_relations';

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
        'article_id'
    ];

    protected $fillable = [
        'user_id',
        'article_id',
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }
}

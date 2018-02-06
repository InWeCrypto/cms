<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ArticleCategoryCc extends Model
{
	protected $table = 'category_article_relations';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->select('id','name','img');
    }
}

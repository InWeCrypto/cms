<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ArticleTag extends Model
{
	protected $table   = 'article_tags';
	protected $guarded = [];

    public function tagInfo()
    {
        return $this->hasOne('App\Model\Tag', 'id', 'tag_id');
    }
}

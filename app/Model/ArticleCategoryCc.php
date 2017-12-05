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
	protected $table = 'article_category_cc';

	public function article()
	{
        return $this->hasOne('App\Model\Article','id');
    }

    public function category()
    {
		return $this->hasOne('App\Model\Category','id', 'category_id')->select(['id','name','long_name']);
    }
}
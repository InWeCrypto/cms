<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class Article extends Model
{
	protected $table = 'articles';

    protected $hidden = [
        'content',
        'seo_title',
        'seo_keyworks',
        'seo_desc'
    ];

    protected $fillable = [
        'category_id',
        'type',
        'title',
        'author',
        'img',
        'url',
        'video',
        'desc',
        'content',
        'sort',
        'click_rate',
        'lang',
        'is_hot',
        'is_top',
        'is_scroll',
        'is_sole',
        'enable',
    ];

    protected $casts = [
        'is_scroll' => 'boolean',
        'is_top' => 'boolean',
        'is_hot' => 'boolean',
        'is_sole' => 'boolean',
        'enable' => 'boolean'
    ];

	const TXT     = 1; // 文本
	const IMG     = 2; // 图文
	const VIDEO   = 3; // 视频
    const TRADING = 4; // 交易
	const DESC    = 5; // 项目介绍

    public static $types = [
        self::TXT => '文本',
        self::IMG => '图文',
        self::VIDEO => '视频',
        self::TRADING => '交易观点',
    ];

	protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('is_top','DESC')
			               ->orderBy('is_hot','DESC')
			               ->orderBy('sort','DESC')
			               ->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });

    }

    public function articleCategoryCc()
    {
        return $this->hasMany(ArticleCategoryCc::class, 'article_id', 'id');
    }

    public function articleUser()
    {
        return $this->belongsTo(ArticleUser::class, 'id', 'article_id');
    }

    public function comment()
    {
        return $this->hasMany(ArticleComment::class, 'article_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id','name','img', 'type');
    }

    public function articleTagInfo()
    {
        return $this->belongsToMany(ArticleTagInfo::class, 'article_tag_relations', 'article_id', 'tag_id');
    }

    public function articleTag()
    {
        return $this->hasMany(ArticleTag::class, 'article_id');
    }



}

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
	protected $fillable = [
        'title',
        'desc',
        'category_id',
        'content',
        'sort',
        'is_top',
        'is_hot',
        'is_scroll',
        'is_sole',
        'status',
        'enable',
        'click_rate',
        'type',
        'img',
        'url',
        'video',
	];

    protected $casts = [
        'enable' => 'boolean',
        'is_scroll' => 'boolean',
        'is_top' => 'boolean',
        'is_hot' => 'boolean',
    ];


	const RELEASE      = 1;  // 发布 状态
	const WAIT_AUDIT   = 0;  // 待审核 状态
	const DISCARD      = -1; // 作废 状态

	static $status = [
				self::RELEASE,
				self::WAIT_AUDIT,
				self::DISCARD
				];

	const ALL        = 0;
	const TXT        = 1;
	const IMG_TXT    = 2;
	const VIDEO      = 3;
	const DOWN       = 4;
	const ICO        = 5;
	const PROJECT_DESC = 6;

	const CAROUSEL_IMG = 7; // 首页轮播图
	static $type  = [
		self::TXT => '快讯',
		self::IMG_TXT => '图文',
		self::VIDEO => '视频',
		self::DOWN => '下载',
		self::ALL => '快讯,图文,视频',

		// self::ICO => 'ico 测评',
		self::PROJECT_DESC => '项目介绍',
	];

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enable', function(Builder $builder) {
			return $builder->where('enable','1');
        });

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('is_top','DESC')
			               ->orderBy('is_hot','DESC')
			               ->orderBy('sort','ASC')
			               ->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });
    }

	public function scopeStatus($query, $status = self::RELEASE)
	{
		return $query->where('status', $status);
	}

	public function scopeIsScroll($query)
	{
		return $query->where('is_scroll', 1);
	}

	public function scopeOfCatId($query, $cat_id = 0)
	{
		return $query->where('category_id', $cat_id);
	}

	public function scopeOfType($query, $type = self::TXT)
	{
		if($type == self::IMG_TXT){
			$type = [self::TXT,self::IMG_TXT];
		}
		if($type == self::ALL){
			$type = [self::TXT,self::IMG_TXT,self::VIDEO];
		}
		if(is_array($type)){
			return $query->whereIn('type', $type);
		}
		return $query->where('type', $type);
	}

	public function searchKeyword($query, $keyword)
	{
		$type = [
			self::TXT,
			self::IMG_TXT,
			self::VIDEO
		];
$sql = <<<EOT
select *
from "articles"
where type in (:type)
and to_tsvector('testzhcfg', content) @@ to_tsquery('testzhcfg', :keyword)
EOT;

		$return = DB::select($sql, ['keyword'=> $keyword, 'type'=> '1,2,3']);
		// dd($return->toSql());
		return $return;
		// return $query->whereRaw("to_tsvector('testzhcfg', content) @@ to_tsquery('testzhcfg','?')", [$keyword]);
					// ->whereIn('type', [
					// 	self::TXT,
					// 	self::IMG_TXT,
					// 	self::VIDEO
					// ]);
	}

	public function comments()
	{
		return $this->hasMany('App\Model\Comment');
	}
	public function commentsCount()
	{
	  return $this->hasOne('App\Model\Comment')
			      ->selectRaw('article_id, count(*) as amount')
			      ->groupBy('article_id');
	}
    // 资讯标签
    public function articleTag()
    {
        return $this->hasMany('App\Model\ArticleTag','article_id','id');

    }
    // 资讯抄送
    public function ccCategory()
    {
        return $this->hasMany('App\Model\ArticleCategoryCc','article_id','id');
    }
}

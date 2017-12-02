<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Category extends Model
{
	protected $table   = 'categorys';
	protected $guarded = [];
	protected $hidden = [
							'enable',
							'is_show',
							'status',
							'created_at',
							'updated_at',
							't_id',
							'p_id'
						];

	const RELEASE      = 1;  // 发布 状态
	const WAIT_AUDIT   = 0;  // 待审核 状态
	const DISCARD      = -1; // 作废 状态
	static $status = [
				self::RELEASE,
				self::WAIT_AUDIT,
				self::DISCARD
				];
	const NONE              = 0;             // 无
	const ARTICLE           = 1;             // 文章
	const CAROUSEL          = 2;        	 // 轮播
	const DOWNLOAD          = 3;             // 下载
	const VIDEO             = 4;         	 // 视频

	const ONLINE            = 5;             // 上线项目
	const WAIT_ONLINE       = 6;		     // 等待上线项目

	const CROWDFUNDING      = 7;			 // 众筹中 项目
	const SOON_CROWDFUNDING = 8;			 // 即将众筹

	const PROJECT_ITME_PRIC = 9;             // 实时价格
	const PROJECT_MARKETS   = 10;            // 市场交易
	const PROJECT_DESC      = 11;            // 简介
	static $type = [
					self::NONE              => '无',
					self::ARTICLE           => '文章栏目',
					self::CAROUSEL          => '轮播栏目',
					self::DOWNLOAD          => '下载栏目',
					self::VIDEO             => '视频栏目',

					self::ONLINE            => '上线项目',
					self::WAIT_ONLINE       => '等待上线项目',
					self::CROWDFUNDING      => '众筹中项目',
					self::SOON_CROWDFUNDING => '即将众筹项目'
				];
	static $project_category = [
		self::ONLINE            => '上线中',
		self::CROWDFUNDING      => '众筹中',
		self::SOON_CROWDFUNDING => '即将开始众筹',
		self::WAIT_ONLINE       => '即将上线交易',
	];
	const SMALL   = 1; // 小方块
	const STRIP_V = 2; // 竖方块
	const STRIP_T = 3; // 横方块
	const BIG     = 4; // 大方块
	static $grid_type = [
						self::SMALL => '小方块',
						self::STRIP_V => '竖方块',
						self::STRIP_T => '横方块',
						self::BIG => '大方块'
					];
	static $grid_detail = [
						self::SMALL   => ['w'=>'1', 'h'=>'1'],
						self::STRIP_V => ['w'=>'1', 'h'=>'2'],
						self::STRIP_T => ['w'=>'2', 'h'=>'1'],
						self::BIG     => ['w'=>'2', 'h'=>'2']
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

    public function scopeIsProject($query)
    {
    	return $query->whereIn('type', array_keys(self::$project_category));
    }

    public function scopeOfType($query, $type)
    {	
    	return $type ? $query->where('type', $type) : $query;
    }

    public function scopeIsEnable($query, $enable = 1)
    {	
    	return $query->where('enable', $enable);
    }

	public function scopeStatus($query, $status = self::RELEASE)
	{
		return $query->where('status', $status);
	}

	public function article()
	{
		return $this->belongsTo('App\Model\Article','category_id');
	}
	// 
	public function childrens()
	{
		return $this->hasMany('App\Model\Category','p_id','id');
	}
	// 项目浏览器
	public function projectExplorer()
	{
		return $this->hasMany('App\Model\ProjectExplorer','project_id','id');
	}
	// 项目钱包
	public function projectWallet()
	{
		return $this->hasMany('App\Model\ProjectWallet','project_id','id');
	}
	// 项目介绍
	public function projectDetail()
	{
		return $this->hasMany('App\Model\Article','category_id','id');
	}
	// 项目行情
	public function ProjectTimePrice()
	{
		return $this->hasMany('App\Model\ProjectTimePrice','project_id','id');
	}
	// 项目市场
	public function ProjectMarket()
	{
		return $this->hasMany('App\Model\ProjectMarket','project_id','id');
	}
	// 项目标签
	public function ProjectTag()
	{
		return $this->hasMany('App\Model\ProjectTag','project_id','id');
	}

}

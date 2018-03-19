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
        'source_url',
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
    const TRADING = 4; // 交易-图文
    const DESC    = 5; // 项目介绍
	const FILE    = 6; // 项目介绍
    const TRADING_VIDEO = 7; // 交易-视8
    const HELP_TXT   = 8; // 帮助中心
    const HELP_IMG   = 9; // 帮助中心
    const HELP_VIDEO = 10; // 帮助中心
    const HELP_FILE  = 11; // 帮助中心
    const VIEWPOINT_TXT = 12;
    const VIEWPOINT_IMG = 13;
    const VIEWPOINT_VIDEO = 14;
    const VIEWPOINT_FILE = 15;

    public static $types = [
        self::TXT => '文本',
        self::IMG => '图文',
        self::VIDEO => '视频',
        self::FILE => '文件',
        self::TRADING => '交易观点',
        self::TRADING_VIDEO => '交易观点-视频',
        self::VIEWPOINT_TXT => '观点-文本',
        self::VIEWPOINT_IMG => '观点-图文',
        self::VIEWPOINT_VIDEO => '观点-视频',
        self::VIEWPOINT_FILE => '观点-文件',
    ];

    public static $trading = [
        self::TRADING => '交易观点',
        self::TRADING_VIDEO => '交易观点-视频',
    ];

    public static $viewpoint = [
        self::VIEWPOINT_TXT => '观点-文本',
        self::VIEWPOINT_IMG => '观点-图文',
        self::VIEWPOINT_VIDEO => '观点-视频',
        self::VIEWPOINT_FILE => '观点-文件',
    ];

    public static $help = [
        self::HELP_TXT   => '帮助中心-文本', // 帮助中心
        self::HELP_IMG   => '帮助中心-图文', // 帮助中心
        self::HELP_VIDEO => '帮助中心-视频', // 帮助中心
        self::HELP_FILE  => '帮助中心-文件', // 帮助中心
    ];

	protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('created_at','DESC')
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

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
	protected $table    = 'categorys';

    protected $fillable = [
        'type',
        'name',
        'long_name',
        'desc',
        'img',
        'url',
        'website',
        'unit',
        'token_holder',
        'room_id',
        'sort',
        'is_hot',
        'is_top',
        'is_scroll',
        'enable',
        'cover_img'
    ];

    protected $hidden = [
        'seo_title',
        'seo_keyworks',
        'seo_desc',
    ];

    protected $casts = [
        'is_scroll' => 'boolean',
        'is_top' => 'boolean',
        'is_hot' => 'boolean',
    ];

    protected $appends = [
        'type_name',
    ];

	// const NONE              = 0;
	const TRADING           = 1; // 交易中
	const ACTIVE            = 2; // 众筹中
	const UPCOMING          = 3; // 即将众筹
	const ENDED             = 4; // 众筹结束

    public static $types = [
        self::TRADING => '交易中',
        self::ACTIVE => '众筹中',
        self::UPCOMING => '即将众筹',
        self::ENDED => '众筹结束',
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

    protected function getTypeNameAttribute()
    {
        $type = $this->type ?: 0;
        $str = '';
        switch ($type){
            case self::TRADING :
            $str = trans('custom.TRADING');
            break;
            case self::ACTIVE :
            $str = trans('custom.ACTIVE');
            break;
            case self::UPCOMING :
            $str = trans('custom.UPCOMING');
            break;
            case self::ENDED :
            $str = trans('custom.ENDED');
            break;
        }
        return $str;
    }

    public function scopeOfType($query, $type)
    {
    	return $type ? $query->where('type', $type) : $query;
    }

    public function scopeIsEnable($query, $enable = 1)
    {
    	return $query->where('enable', $enable);
    }

    public function categoryUser()
    {
        return $this->belongsTo(CategoryUser::class, 'id', 'category_id');
        // return $this->belongsToMany(\App\User::class, 'category_user_relations', 'category_id', 'user_id');
    }

	public function article()
	{
		return $this->belongsTo('App\Model\Article','category_id');
	}
    // 获取项目最后更新的文章
    public function lastArticle()
    {
        return $this->hasOne(Article::class, 'category_id')
                    ->whereIn('type', [Article::IMG, Article::VIDEO, Article::TRADING])
                    ->orderBy('created_at','DESC')
                    ->orderBy('id', 'DESC')
                    ->select('id', 'category_id', 'title', 'desc', 'img', 'created_at', 'url');
    }
	// 项目浏览器
	public function categoryExplorer()
	{
		return $this->hasMany(CategoryExplorer::class,'category_id','id');
	}
	// 项目钱包
	public function categoryWallet()
	{
		return $this->hasMany(CategoryWallet::class,'category_id','id');
	}
    // 项目结构
    public function categoryStructure()
    {
        $lang = \Request::header('lang', config('app.locale'));
        return $this->hasMany(CategoryStructure::class,'category_id','id');
    }
	// 项目简介
	public function categoryDesc()
	{
		return $this->hasOne(CategoryDesc::class,'category_id','id');
	}
	// 项目媒体
	public function categoryMedia()
	{
		return $this->hasMany(CategoryMedia::class,'category_id','id');
	}

}

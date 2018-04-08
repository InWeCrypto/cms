<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryUser extends Model
{
    const COMMENT_TAG_POPULAR = 1;
    const COMMENT_TAG_RECENTLY = 2;
    const COMMENT_TAG_MOST = 3;
    const COMMENT_TAG_POSITIVE = 4;
    const COMMENT_TAG_NEGATIVE = 5;
    const COMMENT_TAG_INVEST = 6;
    const COMMENT_TAG_OBSERVE = 7;
    const COMMENT_TAG_INVESTED = 8;

    public static $comment_tag = [
        'en'=>[
            self::COMMENT_TAG_POPULAR => 'Popular',
            self::COMMENT_TAG_RECENTLY => 'Recently',
            self::COMMENT_TAG_MOST => 'Most-Helpful',
            self::COMMENT_TAG_POSITIVE => 'Positive',
            self::COMMENT_TAG_NEGATIVE => 'Negative',
            self::COMMENT_TAG_INVEST => 'Want to invest',
            self::COMMENT_TAG_OBSERVE => 'Observe',
            self::COMMENT_TAG_INVESTED => 'Have invested',
        ],
        'zh'=>[
            self::COMMENT_TAG_POPULAR => '热门',
            self::COMMENT_TAG_RECENTLY => '最新',
            self::COMMENT_TAG_MOST => '最有价值',
            self::COMMENT_TAG_POSITIVE => '推荐',
            self::COMMENT_TAG_NEGATIVE => '不推荐',
            self::COMMENT_TAG_INVEST => '想参投',
            self::COMMENT_TAG_OBSERVE => '围观',
            self::COMMENT_TAG_INVESTED => '参投过',
        ]
    ];

	protected $table = 'category_user_relations';

    protected $appends = [
        'category_comment_tag_name'
    ];
	protected $fillable = [
        'category_id',
        'user_id',
        'is_favorite',
        'is_market_follow',
        'market_hige',
        'market_lost',
        'score',
        'is_top',
        'is_category_comment',
        'category_comment_tag_id',
        'category_comment',
        'category_comment_enable',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'is_favorite' => 'boolean',
        'is_market_follow' => 'boolean',
        'is_favorite_dot' => 'boolean',
        'is_top' => 'boolean',
        'category_comment_enable' => 'boolean'
    ];


    public static function getCommentTags($lang = '')
    {
        $lang = $lang ?: config('app.locale');
        return !empty(self::$comment_tag[$lang]) ? self::$comment_tag[$lang] : '';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['id','name','long_name','unit', 'type']);
    }

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'user_id')->select(['id','name','img','email']);
    }

    public function getCategoryCommentTagNameAttribute()
    {
        $tag_id = $this->category_comment_tag_id;
        $tags = self::getCommentTags();
        return !empty($tags[$tag_id]) ? $tags[$tag_id] : '';
    }

    public function comment()
    {
        return $this->hasMany(CategoryUserComment::class, 'category_user_id');
    }

    public function userClickComment()
    {
        return $this->hasMany(CategoryUserClickComment::class, 'category_user_id');
    }

    public function userClickCommentUp()
    {
        return $this->hasMany(CategoryUserClickComment::class, 'category_user_id')->where('up', 1);
    }

    public function userClickCommentDown()
    {
        return $this->hasMany(CategoryUserClickComment::class, 'category_user_id')->where('down', 1);
    }

    public function userClickCommentEqual()
    {
        return $this->hasMany(CategoryUserClickComment::class, 'category_user_id')->where('equal', 1);
    }

}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class IcoAssessArticleComment extends Model
{
	protected $guarded = [];
	protected $table   = 'ico_assess_article_comments';
	const RELEASE      = 1;  // 发布 状态
	const WAIT_AUDIT   = 0;  // 待审核 状态
	const DISCARD      = -1; // 作废 状态
	
	static $status = [
				self::RELEASE,
				self::WAIT_AUDIT,
				self::DISCARD
				];

	protected static function boot()
    {
        parent::boot();
    }

	public function scopeStatus($query, $status = self::RELEASE)
	{
		return $query->where('status', $status);
	}

	public function article()
	{
		return $this->belongsTo('App\Model\IcoAssessArticle','ico_article_id');
	}

	public function parentComment()
	{
		return $this->hasOne('App\Model\IcoAssessArticleComment','id','p_id');
	}

	public function user()
	{
		return $this->hasOne('App\Model\User','id','user_id');
	}

	public function scopeOfArticle($query, $ico_article_id, $comment_p_id)
	{
		return $query->where('ico_article_id', $ico_article_id)->where('p_id', $comment_p_id);
	}
}
<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Ad extends Model
{
    protected $guarded = [];

	const RELEASE      = 1; // 发布 状态
	const WAIT_AUDIT   = 0; // 待审核 状态
	const DISCARD      = -1;

	static $status = [
				self::RELEASE,
				self::WAIT_AUDIT,
				self::DISCARD
				];

    protected $casts = [
        'enable' => 'boolean',
    ];


	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('sort','ASC')
                           ->orderBy('created_at','DESC')
			               ->orderBy('id','DESC');
        });
    }

	public function scopeStatus($query, $status = self::RELEASE)
	{
		return $query->where('status', $status);
	}
}

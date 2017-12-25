<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CandyBow extends Model
{
	protected $table = 'candy_bows';
	protected $fillable = [
		'name',
		'img',
		'desc',
		'url',
		'year',
		'month',
		'day',
		'project_id',
        "enable",
        "sort",
	];

    protected $casts = [
        'enable' => 'boolean',
        'is_scroll' => 'boolean'
    ];

	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function(Builder $builder) {
			return $builder->orderBy('sort','DESC')
			               ->orderBy('updated_at','DESC')
			               ->orderBy('id','DESC');
        });
    }

	public function scopeIsEnable($query)
	{
		return $query->where('enable', 1);
	}
}

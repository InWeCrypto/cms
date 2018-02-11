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
    protected $casts = [
        'enable' => 'boolean'
    ];
	protected $fillable = [
		'name',
		'img',
		'desc',
		'url',
		'year',
		'month',
		'day',
		'category_id',
        'lang',
        'enable',
        'sort',
        'content'
	];
    protected $hidden = [
        'content'
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

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id', 'name', 'long_name', 'unit', 'type');
    }
}

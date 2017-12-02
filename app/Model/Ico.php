<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Ico extends Model
{
	protected $table   = 'icos';
	protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enable', function(Builder $builder) {
            return $builder->where('enable','1');
        });

        static::addGlobalScope('sort', function(Builder $builder) {
            return $builder->orderBy('sort','DESC')
                           ->orderBy('id','ASC');
        });
    }

    public function userTicker()
    {
        return $this->hasOne('App\Model\UserTicker');
    }
}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class IcoDetail extends Model
{
	protected $table   = 'ico_details';
	protected $guarded = [];

    public function scopeOfProject($query, $project_id)
    {
        return $query->where('project_id', $project_id);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('enable', function(Builder $builder) {
            return $builder->where('enable','1');
        });

        static::addGlobalScope('sort', function(Builder $builder) {
            return $builder->orderBy('sort','DESC')
                           ->orderBy('id','DESC');
        });
    }

}

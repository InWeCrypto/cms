<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class ProjectMarket extends Model
{
	protected $table   = 'project_markets';
	protected $guarded = [];

    public function scopeOfProject($query, $project_id)
    {
        return $query->where('project_id', $project_id);
    }

}

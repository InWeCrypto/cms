<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class CategoryDesc extends Model
{
	protected $table = 'category_descs';

    protected $fillable = [
        'category_id',
        'start_at',
        'end_at',
        'content',
        'lang',
        'enable',
    ];
}

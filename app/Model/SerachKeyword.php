<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class SerachKeyword extends Model
{
	protected $table = 'serach_keywords';

    protected $fillable = [
        'id',
        'lang',
        'created_at',
        'updated_at',
        'enable',
        'desc',
        'name'
    ];

    protected $casts = [
        'enable' => 'boolean'
    ];
}

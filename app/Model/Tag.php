<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class Tag extends Model
{
	protected $table   = 'tags';
	protected $fillable = [
        'name',
        'desc'
    ];

    
}

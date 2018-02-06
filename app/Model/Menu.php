<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class Menu extends Model
{
	protected $table    = 'menus';

    protected $fillable = [
        'name',
        'desc',
        'url',
        'lang',
        'enable',
    ];

    protected $casts = [
        'enable' => 'bollean'
    ];

}

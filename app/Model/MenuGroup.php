<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class MenuGroup extends Model
{
	protected $table    = 'menu_groups';

    protected $fillable = [
        'group_name',
        'enable',
    ];

    protected $casts = [
        'enable' => 'bollean'
    ];

}

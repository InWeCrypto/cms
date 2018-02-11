<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class AdminMenuGroup extends Model
{
	protected $table = 'menu_user_groups';

    protected $fillable = [
        'user_id',
        'group_id',
    ];

    public function menus()
    {
        // return $this->hasMany(MenuGroupRelation::class, 'group_id', 'group_id');
        return $this->belongsToMany(Menu::class, 'menu_group_relations', 'group_id', 'menu_id');
    }

    public function info()
    {
        return $this->hasOne(MenuGroup::class, 'id','group_id');
    }

}

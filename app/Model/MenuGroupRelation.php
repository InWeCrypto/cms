<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class MenuGroupRelation extends Model
{
	protected $table    = 'menu_group_relations';

    protected $fillable = [
        'group_id',
        'menu_id',
    ];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id','menu_id');
    }

    public function info()
    {
        return $this->hasOne(Menu::class, 'id','menu_id');
    }


}

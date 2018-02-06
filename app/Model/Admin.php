<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Article
 * @package App\Model
 */
class Admin extends Model
{
	protected $table = 'admins';

    protected $fillable = [
        'name',
        'phone',
        'img',
        'email',
    ];

    public function menuGroup()
    {
        return $this->hasOne(AdminMenuGroup::class, 'user_id');
    }

}

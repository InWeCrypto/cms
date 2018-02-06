<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

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

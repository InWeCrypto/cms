<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class User extends Model
{

    protected $table = 'users';
	/**
     * @var array
     */
    protected $hidden = [
        "password",
    ];
    /**
     * @var array
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "nickname",
        "sex",
        "img",
        "phone",
        "open_id"
    ];

	
}
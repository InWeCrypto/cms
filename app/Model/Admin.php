<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Article
 * @package App\Model
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
	protected $fillable = [
        'name',
        'password',
        'email',
        'img',
        'remember_token',
        'phone',
	];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey(); // Eloquent model method
    }

    /**
     * @return array
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
	
}
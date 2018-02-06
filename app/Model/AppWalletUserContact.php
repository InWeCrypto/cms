<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class AppWalletUserContact extends Model
{
    protected $connection = 'pgsql_app';
	protected $table = 'user_contacts';
    protected $fillable = [
        'category_id',
        'name',
        'address',
        'user_id'
    ];

    public function scopeOfUserId($query, $user_id)
	{
		return $user_id ? $query->where('user_id', $user_id) : $query;
	}

	public function scopeOfCategoryId($query, $cat_id)
	{
		return $cat_id ? $query->where('category_id', $cat_id) : $query;
	}
}

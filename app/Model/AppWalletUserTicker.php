<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class AppWalletUserTicker extends Model
{
    protected $connection = 'pgsql_app';
	protected $table = 'user_tickers';

    public function scopeOfUserId($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : $query;
    }


    public function ticker()
    {
        return $this->hasOne(AppWalletIcoList::class, 'id', 'ico_id');
    }
}

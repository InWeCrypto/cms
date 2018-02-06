<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class AppWalletIcoList extends Model
{
    protected $connection = 'pgsql_app';
	protected $table = 'ico_list';

    public function userTicker()
    {
        return $this->hasOne('App\Model\UserTicker');
    }
}

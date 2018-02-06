<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Article
 * @package App\Model
 */
class AppWalletUser extends Model
{
    protected $connection = 'pgsql_app';
	protected $table = 'users';
}

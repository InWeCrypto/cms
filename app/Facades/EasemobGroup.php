<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EasemobGroup extends Facade
{
    protected static function getFacadeAccessor()
	{
		return 'easemob_group_api';
	}
}

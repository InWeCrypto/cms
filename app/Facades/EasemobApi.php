<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EasemobApi extends Facade
{
    protected static function getFacadeAccessor()
	{
		return 'easemob_api';
	}
}

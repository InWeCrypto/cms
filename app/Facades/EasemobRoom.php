<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EasemobRoom extends Facade
{
    protected static function getFacadeAccessor()
	{
		return 'easemob_room_api';
	}
}

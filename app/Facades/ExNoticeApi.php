<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExNoticeApi extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'ex_notice_api';
	}
}

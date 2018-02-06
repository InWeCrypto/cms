<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MailVerify extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'mail_verify';
	}
}

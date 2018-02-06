<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SmsVerify extends Facade
{

    const listKey = 'KEY:SMSVERIFY:';
	protected static function getFacadeAccessor()
	{
		return 'sms_verify';
	}
}

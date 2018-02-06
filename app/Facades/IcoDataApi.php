<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class IcoDataApi extends Facade
{
    public static $valid_interval = [
                                        '1m','5m','15m','30m',
                                        '1h','2h','4h','6h','12h',
                                        '1d',
                                        '1w'
                                    ];
	protected static function getFacadeAccessor()
	{
		return 'ico_data_api';
	}
}
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OSS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'aliyun_oss';
    }
}
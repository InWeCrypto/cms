<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class TestConsole extends Command
{
    protected $signature = 'testconsole';

    protected $description = '这是一个测试artisan的描述';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('这是我写的log');
    }
}

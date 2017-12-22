<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('aliyun_oss', function () {
            return new \App\Services\OssService();
        });

        $this->app->singleton('sms_verify', function () {
            return new \App\Services\SmsVerifyService();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

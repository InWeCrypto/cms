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

        $this->app->singleton('mail_verify', function(){
            return new \App\Services\MailVerifyService();
        });

        $this->app->singleton('sms_verify', function () {
            return new \App\Services\SmsVerifyService();
        });

        $this->app->singleton('easemob_api', function(){
            return new \App\Services\EasemobApiService();
        });
        $this->app->singleton('easemob_user_api', function(){
            return new \App\Services\EasemobUserService();
        });
        $this->app->singleton('easemob_group_api', function(){
            return new \App\Services\EasemobGroupService();
        });
        $this->app->singleton('easemob_room_api', function(){
            return new \App\Services\EasemobRoomService();
        });
        $this->app->singleton('easemob_msg_api', function(){
            return new \App\Services\EasemobMsgService();
        });

        $this->app->singleton('ico_data_api', function () {
            return new \App\Services\IcoDataApiService();
        });

        $this->app->singleton('ex_notice_api', function() {
            return new \App\Services\ExNoticeApiService();
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

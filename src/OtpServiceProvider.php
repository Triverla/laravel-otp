<?php

namespace Triverla\LaravelOtp;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/otp.php' => config_path('otp.php'),
            ], 'otp-config');
        }

        $this->app->bind('laravel-otp', function () {
            ['digits' => $digits, 'expires' => $expires] = config('otp');

            return (new Otp(Cache::store()))->digits($digits)->expires($expires);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/otp.php', 'otp');
    }
}

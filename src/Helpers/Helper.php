<?php

use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Triverla\LaravelOtp\Otp;

if (!function_exists('otp')) {
    function otp(string $directory = null): Otp
    {
        if ($directory) {
            $store = new Repository(new FileStore(new Filesystem(), $directory));

            return new Otp($store);
        }

        return app('laravel-otp');
    }
}

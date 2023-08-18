<?php

namespace Triverla\LaravelOtp\Facades;

use Illuminate\Support\Facades\Facade;
use Triverla\LaravelOtp\Helpers\OtpNotificationRequest;

class Otp extends Facade
{
    /**
     * @method static \Triverla\LaravelOtp\Otp digits(int $digits)
     * @method static \Triverla\LaravelOtp\Otp expires(int $minutes)
     * @method static string generate(string $key)
     * @method static string regenerate(string $key, bool $changeOTP)
     * @method static bool verify(mixed $otp, string $key)
     * @method static bool notify(OtpNotificationRequest $request)
     **/
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-otp';
    }
}

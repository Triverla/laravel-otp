<?php

return [
    /**
     * This value indicates the number of digits to be generated
     */
    'digits' => env('OTP_LENGTH', 6),

    /**
     * This value indicates the number of minutes until one OTP will be valid
     */
    'expires' => env('OTP_TIMEOUT', 15),

    'transport' => [
        'mailable_class' => env('OTP_MAILABLE_CLASS', Triverla\LaravelOtp\Notifications\NewOtpMail::class),
        'sms_class' => env('OTP_SMS_CLASS', Triverla\LaravelOtp\Notifications\NewOtpSms::class),
        'email' => env('OTP_TRANSPORT_EMAIL', true),
        'sms' => env('OTP_TRANSPORT_SMS', true)
    ],
];

<?php

namespace Triverla\LaravelOtp\Helpers;

class OtpNotificationRequest
{
    public ?string $mobileNumber;
    public ?string $email;
    public string $otp;

    /**
     * OtpRequestObject constructor.
     * @param string $otp
     * @param string|null $mobileNumber
     * @param string|null $email
     */
    public function __construct(string $otp, ?string $email = null, ?string $mobileNumber = null)
    {
        $this->otp = $otp;
        $this->mobileNumber = $mobileNumber;
        $this->email = $email;
    }
}

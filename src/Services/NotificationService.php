<?php

namespace Triverla\LaravelOtp\Services;

use Exception;
use Illuminate\Support\Facades\Notification;
use Triverla\LaravelOtp\Exceptions\InvalidArgumentException;
use Triverla\LaravelOtp\Helpers\OtpNotificationRequest;
use Triverla\LaravelOtp\Notifications\NewOtpMail;
use Triverla\LaravelOtp\Notifications\NewOtpSms;

class NotificationService
{
    private OtpNotificationRequest $data;

    public function __construct(OtpNotificationRequest $data)
    {
        $this->data = $data;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if (empty($this->data->otp)) {
            throw new InvalidArgumentException('OTP is required');
        }

        if (empty($this->data->mobileNumber) && empty($this->data->email)) {
            throw new InvalidArgumentException('Email and Mobile Number cannot be blank');
        }

        try {
            $mailableClass = config('otp.transport.mailable_class', NewOtpMail::class);
            $smsClass = config('otp.transport.sms_class', NewOtpSms::class);

            if (config('otp.transport.email') && !empty($this->data->email)) {
                Notification::route('mail', $this->data->email)
                    ->notify(new $mailableClass($this->data->otp));
            }

            if (config('otp.transport.sms') && !empty($this->data->mobileNumber)) {
                Notification::route('nexmo', $this->data->mobileNumber)
                    ->notify(new $smsClass($this->data->otp));
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

}

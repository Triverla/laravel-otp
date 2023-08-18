<?php

namespace Triverla\LaravelOtp\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;

class NewOtpSms extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable): array
    {
        return ['nexmo'];
    }

    public function toSMS($notifiable): MailMessage
    {
        return (new NexmoMessage)
        ->content("Your OTP: {$this->otp}");
    }
}

<?php

namespace Triverla\LaravelOtp\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOtpMail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('One Time Password(OTP) for verification')
            ->line("Here is your one time password for verification <b>{$this->otp}</b>")
            ->line('Please use within 15 mins and do not share OTP with anyone.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

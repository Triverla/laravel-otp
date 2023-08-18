<?php

namespace Triverla\LaravelOtp;

use Closure;
use DateInterval;
use Exception;
use Illuminate\Cache\Repository;
use Triverla\LaravelOtp\Helpers\OtpNotificationRequest;
use Triverla\LaravelOtp\Services\NotificationService;

class Otp
{

    protected int $expires = 15;

    protected int $digits = 6;

    private Repository $store;


    public function __construct(Repository $store)
    {
        $this->store = $store;
    }

    /**
     * @return int
     */
    protected function getCurrentTime(): int
    {
        return time();
    }

    /**
     * @param $expires
     * @return $this
     */
    public function expires($expires): self
    {
        $seconds = (int)$expires * 60;

        if ($seconds > 0) {
            $this->expires = $seconds;
        }

        return $this;
    }

    /**
     * @param $digits
     * @return $this
     */
    public function digits($digits): self
    {
        $intDigits = (int)$digits;

        if ($intDigits > 0) {
            $this->digits = $intDigits;
        }

        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    public function generate($key): string
    {
        $secret = sha1(uniqid());
        $expires = $this->expires;
        $ttl = DateInterval::createFromDateString("{$expires} seconds");
        $this->store->put($this->keyFor($key), $secret, $ttl);

        return $this->calculate($secret);
    }

    /**
     * @param $key
     * @param $changeOtp
     * @return string
     */
    public function regenerate($key, $changeOtp = true): string
    {
        if ($changeOtp) {
            $this->forget($key);
        }

        $secret = ($changeOtp) ? sha1(uniqid()) : $this->store->get($this->keyFor($key));
        $expires = $this->expires;
        $ttl = DateInterval::createFromDateString("{$expires} seconds");
        $this->store->put($this->keyFor($key), $secret, $ttl);

        return $this->calculate($secret);
    }

    /**
     * @param $code
     * @param $key
     * @return bool
     */
    public function verify($code, $key): bool
    {
        $secret = $this->store->get($this->keyFor($key));

        if (empty($secret)) {
            return false;
        }

        if ($code == $this->calculate($secret)) {
            return true;
        }

        $factor = ($this->getCurrentTime() - floor($this->expires / 2)) / $this->expires;

        return $code == $this->calculate($secret, $factor);
    }

    /**
     * @param $key
     * @return bool
     */
    public function forget($key): bool
    {
        return $this->store->forget($this->keyFor($key));
    }

    /**
     * @param $key
     * @return string
     */
    protected function keyFor($key): string
    {
        return md5(sprintf('%s-%s', 'laravel-otp', $key));
    }

    /**
     * @param $secret
     * @param $factor
     * @return string
     */
    protected function calculate($secret, $factor = null): string
    {
        $hash = hash_hmac('sha1', $this->timeFactor($factor), $secret, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0xF;

        $hash = str_split($hash);
        foreach ($hash as $index => $value) {
            $hash[$index] = ord($value);
        }

        $binary = (($hash[$offset] & 0x7F) << 24) | (($hash[$offset + 1] & 0xFF) << 16) | (($hash[$offset + 2] & 0xFF) << 8) | ($hash[$offset + 3] & 0xFF);

        $otp = $binary % pow(10, $this->digits);

        return str_pad((string)$otp, $this->digits, '0', STR_PAD_LEFT);
    }

    /**
     * @param $divisionFactor
     * @return string
     */
    protected function timeFactor($divisionFactor): string
    {
        $factor = $divisionFactor ? floor($divisionFactor) : floor($this->getCurrentTime() / $this->expires);

        $text = [];
        for ($i = 7; $i >= 0; $i--) {
            $text[] = ($factor & 0xFF);
            $factor >>= 8;
        }
        $text = array_reverse($text);
        foreach ($text as $index => $value) {
            $text[$index] = chr((int)$value);
        }

        return implode('', $text);
    }

    /**
     * @param OtpNotificationRequest $request
     * @return bool
     */
    public function notify(OtpNotificationRequest $request): bool
    {
        return (new NotificationService($request))->send();
    }
}

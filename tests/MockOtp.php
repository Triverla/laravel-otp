<?php

namespace Triverla\LaravelOtp\Tests;

use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Triverla\LaravelOtp\Otp;

class MockOtp extends Otp
{
    protected ?int $testTime = null;

    public function __construct()
    {
        $directory = './tests/phpunit-cache';
        $store = new Repository(new FileStore(new Filesystem(), $directory));

        parent::__construct($store);
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function getDigits(): int
    {
        return $this->digits;
    }

    public function setTestTime($time)
    {
        $this->testTime = $time;
    }

    protected function getCurrentTime(): int
    {
        if ($this->testTime) {
            return $this->testTime;
        }

        return parent::getCurrentTime();
    }

}

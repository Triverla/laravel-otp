<?php

namespace Triverla\LaravelOtp\Tests;

use Triverla\LaravelOtp\Exceptions\InvalidArgumentException;
use Triverla\LaravelOtp\Facades\Otp;
use Triverla\LaravelOtp\Helpers\OtpNotificationRequest;

class OtpTest extends TestCase
{
    public function test_has_digits_and_expiry()
    {
        $manager = new MockOtp();

        $this->assertTrue($manager->getExpires() > 0);
        $this->assertTrue($manager->getDigits() > 0);
    }

    public function test_digits_can_be_changed()
    {
        $manager = new MockOtp();
        $manager->digits(6);

        $another = (new MockOtp())->digits(8);

        $this->assertEquals(6, $manager->getDigits());
        $this->assertEquals(8, $another->getDigits());
    }

    public function test_expiry_can_be_changed()
    {
        $manager = new MockOtp();
        $manager->expires(5);

        $another = (new MockOtp())->expires(6);

        $this->assertEquals(300, $manager->getExpires());
        $this->assertEquals(360, $another->getExpires());
    }

    public function test_it_can_generate_otp()
    {
        $manager = new MockOtp();

        $this->assertNotEmpty($manager->generate('foo'));

        $otp = Otp::generate('bar');
        $this->assertNotEmpty($otp);

        $this->assertNotEmpty(Otp::generate('baz'));
    }

    public function test_it_will_generate_different_otp_each_time()
    {
        $manager = new MockOtp();
        $this->assertNotEquals($manager->generate('foo'), $manager->generate('foo'));

        $this->assertNotEquals(Otp::generate('bar'), Otp::generate('bar'));
    }

    public function test_it_generates_the_same_number_of_digits()
    {
        $manager = (new MockOtp())->digits(8);

        $this->assertEquals(8, strlen($manager->generate('foo')));

        $manager->digits(6);
        $this->assertEquals(6, strlen($manager->generate('foo')));
    }

    public function test_it_validates_the_otp()
    {
        $manager = new MockOtp();
        $otp = $manager->generate('bar');

        $this->assertFalse($manager->verify($otp, 'foo'));
        $this->assertTrue($manager->verify($otp, 'bar'));

        // It can be validated multiple times
        $this->assertTrue($manager->verify($otp, 'bar'));
        $this->assertTrue($manager->verify($otp, 'bar'));
    }

    public function test_it_can_forget_the_otp()
    {
        $manager = new MockOtp();
        $otp = $manager->generate('bar');

        $this->assertFalse($manager->verify($otp, 'foo'));
        $this->assertTrue($manager->verify($otp, 'bar'));

        $manager->forget('bar');
        $this->assertFalse($manager->verify($otp, 'bar'));
    }

    public function test_otp_will_be_invalid_after_the_expiry()
    {
        $manager = new MockOtp();
        $otp = $manager->generate('foo');
        $manager->setTestTime(time() + ($manager->getExpires() * 100));

        $this->assertFalse($manager->verify($otp, 'foo'));
    }

    public function test_it_can_notify_mail_recipients()
    {
        $manager = new MockOtp();
        $otp = $manager->notify(new OtpNotificationRequest($manager->generate('foo'), 'yusufbenaiah@gmail.com'));

        $this->assertTrue($otp);
    }

    public function test_it_can_notify_sms_recipients()
    {
        $manager = new MockOtp();

        $otp = $manager->notify(new OtpNotificationRequest($manager->generate('foo'), null, '+1234567890'));

        $this->assertTrue($otp);
    }

    public function test_it_wont_notify_without_recipients()
    {
        $manager = new MockOtp();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email and Mobile Number cannot be blank');

        $manager->notify(new OtpNotificationRequest($manager->generate('foo'), null, null));


    }

    public function test_it_wont_notify_without_otp()
    {
        $manager = new MockOtp();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('OTP is required');

        $manager->notify(new OtpNotificationRequest('', 'foo@mail.com', null));

    }

    public function test_it_can_regenerate_otp()
    {
        $manager = new MockOtp();
        $this->assertEquals($manager->generate('foo'), $manager->regenerate('foo', false));

        $this->assertNotEquals(Otp::generate('bar'), Otp::generate('bar'));
    }

    public function test_helper_works()
    {
        $otp = otp()->generate('foo');

        $this->assertNotEmpty($otp);

        $this->assertTrue(otp()->verify($otp, 'foo'));
    }
}

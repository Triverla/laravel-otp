# Laravel OTP Generator with Mail and SMS Notification

This package is for easy setup for OTP validation process. No database required.

## Installation

NB: This package only supports 7.4+

Via Composer

```bash
composer require triverla/laravel-otp
```

### Add Service Provider & Facade

#### For Laravel 5.5+

Once the package is added, the service provider and facade will be auto discovered.

#### For Older versions of Laravel

Add the ServiceProvider to the providers array in `config/app.php`:

```php
Triverla\LaravelOtp\OtpServiceProvider::class
```

Add the Facade to the aliases array in `config/app.php`:

```php
'Otp' => Triverla\LaravelOtp\OtpServiceProvider::class
```

## Publish Config
Once done, publish the config to your config folder using:
```
php artisan vendor:publish --provider="Triverla\LaravelOtp\OtpServiceProvider"
```
This command will create a `config/otp.php` file.

## Env Variables
Add the following Key-Value pair to the `.env` file in the Laravel application

```dotenv
OTP_LENGTH=6
OTP_TIMEOUT=15
OTP_MAILABLE_CLASS=Triverla\LaravelOtp\Notifications\NewOtpMail::class
OTP_SMS_CLASS=Triverla\LaravelOtp\Notifications\NewOtpSms::class
OTP_TRANSPORT_EMAIL=true
OTP_TRANSPORT_SMS=false
```

### Custom Notification Classes

You can use custom classes for your Mail and SMS notifications. You can either add it via `.env` or in `config/otp.php` 

### Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

### Bugs & Issues

If you notice any bug or issues with this package kindly create and issues here [ISSUES](https://github.com/triverla/laravel-monnify/issues)

### Security

If you discover any security related issues, please email yusufbenaiah@gmail.com.

## How can I thank you?

Why not star the github repo and share the link for this repository on Twitter or other social platforms.

Don't forget to [follow me on twitter](https://twitter.com/benaiah_yusuf)!

Thanks!
Benaiah Yusuf

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

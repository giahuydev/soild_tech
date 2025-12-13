<?php

namespace App\Providers;

use App\User\Mail\SendGridTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('sendgrid', function (array $config = []) {
            return new SendGridTransport(
                config('services.sendgrid.api_key')
            );
        });
    }
}
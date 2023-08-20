<?php

namespace App\Providers\Notifications;

use Illuminate\Support\ServiceProvider;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Client\Client;

class SmsRuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Api::class, function ($app) {
            return new Api(new ApiIdAuth(config('services.sms_ru.id')), new Client());
        });
    }

    public function provides(): array
    {
        return [
            Api::class,
        ];
    }
}

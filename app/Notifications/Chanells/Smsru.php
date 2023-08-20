<?php

namespace App\Notifications\Chanells;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Exception\Exception;

class Smsru
{
    public function __construct(
        protected Api $smsRuClient
    ){}

    /**
     * Отправить переданное уведомление.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     * @throws Exception
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (!\App::environment('local')) {
            $this->smsRuClient->smsSend($notification->toSms());
        } else {
            Log::debug('Добавлен новый пост');
        }

    }
}

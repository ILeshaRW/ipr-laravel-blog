<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class Telegram
{
    public function __construct(){}

    /**
     * Отправить переданное уведомление.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        $botToken = config('services.tg_notify.bot_token');
        $channelId = config('services.tg_notify.id_channel');
        $message = $notification->getMessage();

        Http::get('https://api.telegram.org/bot' . $botToken . '/sendMessage?chat_id=' . $channelId . '&text=' . $message);
    }
}

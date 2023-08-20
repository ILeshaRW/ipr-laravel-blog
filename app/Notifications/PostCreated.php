<?php

namespace App\Notifications;

use App\Notifications\Chanells\Smsru;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Zelenin\SmsRu\Entity\Sms;

class PostCreated extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [Smsru::class];
    }

    public function toSms(): Sms
    {
        return new Sms(config('services.sms_ru.to'), 'Добавлен новый пост');
    }
}

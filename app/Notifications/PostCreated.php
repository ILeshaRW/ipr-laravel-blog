<?php

namespace App\Notifications;

use App\Models\Post;
use App\Notifications\Channels\Smsru;
use App\Notifications\Channels\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Zelenin\SmsRu\Entity\Sms;

class PostCreated extends Notification
{
    use Queueable;

    public function __construct(
        protected Post $post
    ){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [Smsru::class, Telegram::class];
    }

    public function toSms(): Sms
    {
        return new Sms(config('services.sms_ru.to'), $this->getMessage());
    }

    public function getMessage(): string
    {
        return 'Добавлен новый пост ' . route('post', [$this->post]);
    }
}

<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreated;

/**
 */
class PostObserver
{
    /**
     * Отправка уведомления о новом посте
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post): void
    {
        $notify = User::find(1);

        $notify->notify(new PostCreated($post));

    }
}

<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\PostCommented;

/**
 * тетс
 */
class CommentObserver
{
    /**
     * Отправка уведомления о комментарии
     *
     * @param Comment $comment
     * @return void
     */
    public function created(Comment $comment): void
    {
        $postAuthor = User::find($comment->post->user_id);
        if ($postAuthor->id === \Auth::id()) {
            return;
        }

        $postAuthor->notify(new PostCommented());
    }
}

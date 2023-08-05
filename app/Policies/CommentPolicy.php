<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function update(?User $user, Comment $comment): bool
    {
        return optional($user)->id === $comment->user_id;
    }

    public function delete(?User $user, Comment $comment): bool
    {
        return (optional($user)->id === $comment->user_id)
            || ($comment->post->user_id === optional($user)->id);
    }
}

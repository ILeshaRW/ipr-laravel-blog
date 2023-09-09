<?php

namespace App\Repositories\Blog;

use App\Models\Comment;
use App\Repositories\Interfaces\ICommentRepository;

class CommentRepository implements ICommentRepository
{

    /**
     * @param array $comment
     * @return Comment
     */
    public function create(array $comment): Comment
    {
        return Comment::create($comment);
    }

    /**
     * @param int $commentId
     * @param array $comment
     * @return void
     */
    public function update(int $commentId, array $comment): void
    {
        Comment::where('id', $commentId)->update($comment);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Comment::where('id', $id)->delete();
    }
}

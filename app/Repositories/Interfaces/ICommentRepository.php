<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;

interface ICommentRepository
{
    /**
     * @param array $comment
     * @return Comment
     */
    public function create(array $comment): Comment;

    /**
     * @param int $commentId
     * @param array $comment
     * @return void
     */
    public function update(int $commentId, array $comment): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}

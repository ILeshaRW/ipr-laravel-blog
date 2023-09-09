<?php

namespace App\Services\Blog;

use App\Models\Comment;
use App\Repositories\Blog\CommentRepository;

/**
 * логика комментариев
 */
class CommentService
{
    public function __construct(
        protected CommentRepository $repository
    ){}

    /**
     * @param array $comment
     * @return Comment
     */
    public function create(array $comment): Comment
    {
        return $this->repository->create($comment);
    }

    /**
     * @param int $commentId
     * @param array $comment
     * @return void
     */
    public function update(int $commentId, array $comment): void
    {
        $this->repository->update($commentId, $comment);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}

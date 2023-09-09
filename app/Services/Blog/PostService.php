<?php

namespace App\Services\Blog;

use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Models\Post;
use App\Repositories\Blog\PostRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Логика постов
 */
class PostService
{
    public function __construct(
        protected PostRepository $repository
    ){}

    /**
     * Создание поста, если у пользователя нет активных постов, то создает пост не активным
     *
     * @param int $userId
     * @param array $post
     * @return Post
     */
    public function create(int $userId, array $post): Post
    {
        $userActivePosts = $this->repository->getPostsByUserIdPaginated($userId, 1, true);

        if ($userActivePosts->count() === 0) {
            $post['active'] = false;
        }

        $post['user_id'] = $userId;

        return $this->repository->create($post);
    }

    /**
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getPostsPaginated(int $page): LengthAwarePaginator
    {
        return $this->repository->getPostsPaginated($page);
    }

    /**
     * @param int $userId
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getUserPostsPaginated(int $userId, int $page): LengthAwarePaginator
    {
        return $this->repository->getPostsByUserIdPaginated($userId, $page);
    }

    /**
     * Получает пост, если пост неактивен и не создан пользователем, то выдает исключение ModelNotFoundException
     *
     * @param int $postId
     * @param int|null $userId
     * @return Post
     */
    public function getPost(int $postId, ?int $userId = null): Post
    {
        $post = $this->repository->getPost($postId);

        if (!$post->active
            && $post->user_id !== $userId
        ) {
            throw new ModelNotFoundException();
        }

        return $post;
    }

    /**
     * @param int $id
     * @param array $post
     * @return void
     */
    public function updatePost(int $id, array $post): void
    {
        $this->repository->update($id, $post);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deletePost(int $id): void
    {
        $this->repository->delete($id);
    }
}

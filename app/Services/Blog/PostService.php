<?php

namespace App\Services\Blog;

use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Models\Post;
use App\Repositories\Blog\PostRepository;

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
     * @param CreatePostRequest $request
     * @return Post
     */
    public function create(CreatePostRequest $request): Post
    {
        $user = $request->user();
        $userActivePosts = $this->repository->getPostByUserId($user->id, true);
        $post = $request->validated();

        if ($userActivePosts->count() === 0) {
            $post['active'] = false;
        }

        $post['user_id'] = $request->user()->id;

        return $this->repository->create($post);
    }
}

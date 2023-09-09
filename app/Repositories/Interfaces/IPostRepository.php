<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IPostRepository
{
    /**
     * Кол-во постов на странице
     */
    public const ON_PAGE = 10;

    /**
     * Получить все активные посты по страницам
     *
     * @param int $page
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getPostsPaginated(int $page = 1, int $limit = self::ON_PAGE): LengthAwarePaginator;

    /**
     * получить все посты пользователя по страницам
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getPostsByUserIdPaginated(int $userId): LengthAwarePaginator;

    /**
     * @param array $post
     * @return Post
     */
    public function create(array $post): Post;

    /**
     * @param int $id
     * @return Post
     */
    public function getPost(int $id): Post;

    /**
     * @param int $id
     * @param array $post
     * @return void
     */
    public function update(int $id, array $post): void;

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;
}

<?php

namespace App\Repositories\Interfaces;

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
     * @return LengthAwarePaginator
     */
    public function getAllPostPagination(): LengthAwarePaginator;

    /**
     * получить все посты пользователя по страницам
     *
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getPostByUserId(int $userId): LengthAwarePaginator;
}

<?php

namespace App\Repositories\Blog;

use App\Models\Post;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements IPostRepository
{

    /**
     * @return LengthAwarePaginator
     */
    public function getAllPostPagination(): LengthAwarePaginator
    {
        return Post::addSelect(
            ['id', 'created_at', 'user_id', 'preview_text', 'title']
        )
            ->orderBy('created_at')
            ->active()
            ->paginate(self::ON_PAGE);
    }

    /**
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getPostByUserId(int $userId): LengthAwarePaginator
    {
        return Post::addSelect(
            ['id', 'created_at', 'user_id', 'preview_text', 'title']
        )
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->paginate(self::ON_PAGE);
    }
}

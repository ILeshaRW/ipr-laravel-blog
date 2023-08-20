<?php

namespace App\Repositories\Blog;

use App\Models\Post;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Contracts\Database\Query\Builder;
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
            ->with(['user' => function(Builder $query) { return $query->select('id', 'name', 'last_name'); }])
            ->withCount('comments')
            ->paginate(self::ON_PAGE);
    }

    /**
     * @param int $userId
     * @param bool $isActive
     * @return LengthAwarePaginator
     */
    public function getPostByUserId(int $userId, bool $isActive = false): LengthAwarePaginator
    {
        $query = Post::addSelect(
            ['id', 'created_at', 'user_id', 'preview_text', 'title']
        )
            ->where('user_id', $userId)
            ->with('user')
            ->withCount('comments')
            ->orderBy('created_at');

        if ($isActive) {
            $query->active();
        }

        return $query->paginate(self::ON_PAGE);
    }

    /**
     * Создать пост
     *
     * @param array $post
     * @return Post
     */
    public function create(array $post): Post
    {
        return Post::create($post);
    }
}

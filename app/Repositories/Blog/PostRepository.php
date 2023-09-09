<?php

namespace App\Repositories\Blog;

use App\Models\Post;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements IPostRepository
{

    /**
     * @param int $page
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getPostsPaginated(int $page = 1, int $limit = self::ON_PAGE): LengthAwarePaginator
    {
        return Post::addSelect(
            ['id', 'created_at', 'user_id', 'preview_text', 'title']
        )
            ->orderBy('created_at')
            ->active()
            ->orWhere('user_id', \Auth::id())
            ->with(['user' => function(Builder $query) { return $query->select('id', 'name', 'last_name'); }])
            ->withCount('comments')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * @param int $userId
     * @param int $page
     * @param bool $isActive
     * @return LengthAwarePaginator
     */
    public function getPostsByUserIdPaginated(int $userId, int $page = 1, bool $isActive = false): LengthAwarePaginator
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

    /**
     * @param int $id
     * @return Post
     */
    public function getPost(int $id): Post
    {
        return Post::addSelect(['id', 'created_at', 'user_id', 'text', 'title', 'active'])
            ->with(
                [
                    'user' => function (Builder $query) {
                        return $query->select('id', 'name', 'last_name');
                    },
                    'comments' => function (Builder $query) {
                        return $query->select(['comment', 'id', 'user_id', 'post_id', 'created_at', 'updated_at'])
                            ->with(
                                [
                                    'user' => function (Builder $query) {
                                        return $query->select(['id', 'name', 'last_name']);
                                    },
                                    'post' => function (Builder $query) {
                                        return $query->select('user_id', 'id');
                                    },
                                ]
                            );
                    },
                ]
            )
            ->findOrFail($id);
    }

    /**
     * @param int $id
     * @param array $post
     * @return void
     */
    public function update(int $id, array $post): void
    {
        Post::where('id', $id)->update($post);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Post::where('id', $id)->delete();
    }
}

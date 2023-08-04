<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\View;

/**
 * Контроллер постов
 */
class PostController extends Controller
{
    /**
     * Получение постов с пагинацией
     *
     * @return View
     */
    public function getPosts(): View
    {
        $posts = Post::addSelect(
            ['id', 'created_at', 'user_id', 'preview_text', 'title']
        )
            ->orderBy('created_at')
            ->paginate(10);

        return view('blog.posts', ['posts' => $posts]);
    }

    /**
     * Получение отдельного поста.
     *
     * @param int $postId
     * @return View
     */
    public function getPostDetail(int $postId): View
    {
        $post = Post::addSelect(['id', 'created_at', 'user_id', 'text', 'title'])
            ->findOrFail($postId);

        return view('blog.post', ['post' => $post]);
    }
}

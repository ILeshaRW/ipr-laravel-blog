<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Http\Requests\Blog\Post\EditPostRequest;
use App\Models\Post;
use App\Repositories\Blog\PostRepository;
use App\Services\Blog\PostService;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер постов
 */
class PostController extends Controller
{

    public function __construct(
        protected PostRepository $repository,
        protected PostService $service
    ){}

    /**
     * Получение постов с пагинацией
     *
     * @return View
     */
    public function getPosts(): View
    {
        $posts = $this->repository->getAllPostPagination();

        return view('blog.posts', ['posts' => $posts]);
    }

    /**
     * Получение постов пользователя
     *
     * @return View
     */
    public function getMyPosts(): View
    {
        $posts = $this->repository->getPostByUserId(Auth::id());

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
            ->active()
            ->findOrFail($postId);

        return view('blog.post', ['post' => $post]);
    }

    /**
     * Страница для создания поста
     *
     * @return View
     */
    public function createPostPage(): View
    {
        return view('blog.post.create');
    }

    /**
     * Страница для редактирования поста
     *
     * @param Post $post
     * @return View
     */
    public function editPostPage(Post $post): View
    {
        return view('blog.post.edit', ['post' => $post]);
    }

    /**
     * Создание поста
     *
     * @param CreatePostRequest $request
     * @return RedirectResponse
     */
    public function createPost(CreatePostRequest $request): RedirectResponse
    {
        $post = $this->service->create($request);

        return redirect()->route('edit_post_page', ['post' => $post->id]);
    }

    /**
     * Страница для редактирования поста
     *
     * @param Post $post
     * @param EditPostRequest $request
     * @return RedirectResponse
     */
    public function editPost(Post $post, EditPostRequest $request): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()->route('edit_post_page', ['post' => $post->id]);
    }

    public function deletePost(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('posts');
    }
}

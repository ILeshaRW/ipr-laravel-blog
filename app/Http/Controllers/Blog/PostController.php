<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Http\Requests\Blog\Post\EditPostRequest;
use App\Http\Requests\Blog\Post\PostListRequest;
use App\Models\Post;
use App\Services\Blog\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер постов
 */
class PostController extends Controller
{
    public function __construct(
        protected PostService $service
    ){}

    /**
     * Получение постов с пагинацией
     *
     * @param PostListRequest $request
     * @return View
     */
    public function getPostsPaginated(PostListRequest $request): View
    {
        $posts = $this->service->getPostsPaginated($request->page ?? 1);

        return view('blog.posts', ['posts' => $posts]);
    }

    /**
     * Получение постов пользователя
     *
     * @param PostListRequest $request
     * @return View
     */
    public function getUserPostsPaginated(PostListRequest $request): View
    {
        $posts = $this->service->getUserPostsPaginated(Auth::id(), $request->page ?? 1);

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
        $post = $this->service->getPost($postId, Auth::id());

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
        $post = $this->service->create(Auth::id(), $request->validated());

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
        $this->service->updatePost($post->id, $request->validated());

        return redirect()->route('edit_post_page', ['post' => $post->id]);
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function deletePost(Post $post): RedirectResponse
    {
        $this->service->deletePost($post->id);

        return redirect()->route('posts');
    }
}

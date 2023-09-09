<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\UpdateCommentRequest;
use App\Http\Requests\Blog\CreateCommentRequest;
use App\Models\Comment;
use App\Services\Blog\CommentService;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер комментариев
 */
class CommentController extends Controller
{

    public function __construct(
        protected CommentService $commentService
    ){}

    /**
     * Создание комментария
     *
     * @param CreateCommentRequest $request
     * @return RedirectResponse
     */
    public function createComment(CreateCommentRequest $request): RedirectResponse
    {
        $comment = $request->validated();
        $comment['user_id'] = $request->user()->id;
        $this->commentService->create($comment);

        return redirect()->route('post', ['postId' => $request->post_id]);
    }

    /**
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function updateComment(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->commentService->update($comment->id, ['comment' => $request->commentText]);

        return redirect()->route('post', ['postId' => $comment->post_id]);
    }

    /**
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function deleteComment(Comment $comment): RedirectResponse
    {
        $this->commentService->delete($comment->id);

        return redirect()->route('post', ['postId' => $comment->post_id]);
    }
}

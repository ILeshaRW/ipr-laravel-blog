<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\UpdateCommentRequest;
use App\Http\Requests\Blog\CreateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер комментариев
 */
class CommentController extends Controller
{
    /**
     * Создание комментария
     *
     * @param CreateCommentRequest $request
     * @return RedirectResponse
     */
    public function createComment(CreateCommentRequest $request): RedirectResponse
    {
        $request->merge(['user_id' => $request->user()->id]);
        Comment::create($request->all());

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
        $comment->updateOrFail([
            'comment' => $request->commentText,
        ]);

        return redirect()->route('post', ['postId' => $comment->post_id]);
    }

    /**
     * @param Comment $comment
     * @return RedirectResponse
     */
    public function deleteComment(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->route('post', ['postId' => $comment->post_id]);
    }
}

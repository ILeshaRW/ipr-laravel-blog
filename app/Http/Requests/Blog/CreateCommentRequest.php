<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для создания комментария
 */
class CreateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => 'required|min:1|max:100',
            'post_id' => 'required|exists:posts,id',
        ];
    }
}

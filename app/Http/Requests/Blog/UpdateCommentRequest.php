<?php

namespace App\Http\Requests\Blog;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'commentText' => 'required|min:1|max:100',
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        $comment = $this->route('comment');
        return $comment && $this->user()->can('update', $comment);
    }
}

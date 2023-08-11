<?php

namespace App\Http\Requests\Blog\Post;

use Illuminate\Foundation\Http\FormRequest;

class EditPostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:100',
            'preview_text' => 'required',
            'text' => 'required',
        ];
    }
}

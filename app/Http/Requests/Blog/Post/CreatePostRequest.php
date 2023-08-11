<?php

namespace App\Http\Requests\Blog\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'preview_text' => 'required',
            'text' => 'required',
        ];
    }
}

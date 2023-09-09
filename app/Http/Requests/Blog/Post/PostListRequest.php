<?php

namespace App\Http\Requests\Blog\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'int',
        ];
    }
}

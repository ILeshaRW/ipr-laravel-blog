<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Посты
 */
class Post extends Model
{
    use HasFactory;

    /**
     * Пользователь что написал комментарий
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Комментарии к посту
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Comment::observe(CommentObserver::class);
        Post::observe(PostObserver::class);
    }
}

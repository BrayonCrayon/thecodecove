<?php

namespace App\Providers;

use App\Helpers\UserHelper;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Comment::observe(CommentObserver::class);
        Post::observe(PostObserver::class);

        Gate::define('is-admin', function() {
            return (new UserHelper())->isAuthUserAdmin();
        });
    }
}

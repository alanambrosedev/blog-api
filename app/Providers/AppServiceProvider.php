<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Models\Article;
use App\Observers\ArticleObserver;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Article::observe(ArticleObserver::class);
        Event::listen(UserRegistered::class, SendWelcomeEmail::class);

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        Gate::define('manage-taxonomy', function ($user) {
            return $user->hasRole(['admin', 'editor']) ? Response::allow() : Response::deny('Only Editors can edit tags and categories');
        });
    }
}

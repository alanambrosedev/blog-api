<?php

namespace App\Providers;

use App\Models\Article;
use App\Policies\ArticlePolicy;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [Article::class => ArticlePolicy::class];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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

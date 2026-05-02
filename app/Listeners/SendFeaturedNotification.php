<?php

namespace App\Listeners;

use App\Mail\ArticleFeatured;
use App\Models\Article;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class SendFeaturedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Article $article): void
    {
        if (! $article->is_featured) {
            return;
        }
        $key = 'featured-mail:'.$article->user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new RuntimeException('Rate limit exceeded for user notifications.');
        }
        RateLimiter::hit($key, 60);

        Mail::to($article->user)->queue(new ArticleFeatured($article));
    }
}

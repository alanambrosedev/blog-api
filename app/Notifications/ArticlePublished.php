<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticlePublished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Article $article)
    {
        $this->onQueue('notifications');
        $this->delay(now()->addSeconds(5));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Your article is published.')
            ->greeting('Hello ' . $notifiable->name)
            ->action('View Article', url('/articles/' . $this->article->slug))
            ->line('Thanks for writing with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'title' => $this->article->title,
            'slug' => $this->article->slug,
        ];
    }
}

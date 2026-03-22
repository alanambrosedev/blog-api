<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'user_id',
        'slug',
        'text',
        'published_at',
        'is_featured',
    ];

    protected $appends = ['reading_time'];

    protected static function booted()
    {
        static::addGlobalScope('published', function ($builder) {
            $builder->where('published_at', '<=', now());
        });
    }

    /**
     * @return BelongsTo<Category, Article>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected function casts()
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->text));
        $minutes = ceil($words / 200);

        return "{$minutes} min read";
    }

    public function scopeByCategory($query, $slug)
    {
        return $query->whereHas('category', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopeByTag($query, $slug)
    {
        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }
}

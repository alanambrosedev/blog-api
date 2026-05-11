<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::deleting(function ($category) {
            $category->articles()->delete();
        });
        static::creating(function ($category) {
            $category->slug = self::generateSlug($category->name);
        });
    }

    public static function generateSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;
        while (self::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$count++;
        }

        return $slug;
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

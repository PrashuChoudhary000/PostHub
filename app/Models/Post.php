<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'published', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


    public function scopeByAuthor(Builder $query, int $userId) : Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeTagged(Builder $query, int $tagId) : Builder
    {
        return $query->whereHas('tags', function (Builder $tagQuery) use ($tagId) {
            $tagQuery->where('tags.id', $tagId);
        });
    }



}

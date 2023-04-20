<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'image',
        'title',
        'meta_title',
        'slug',
        'content',
        'isPublished',
        'isActive',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function reacts(): HasMany
    {
        return $this->hasMany(post_react::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(post_comment::class);
    }
}

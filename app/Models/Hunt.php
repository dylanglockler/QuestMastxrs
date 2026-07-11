<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'slug', 'tagline', 'description', 'city', 'neighborhood', 'cover_image', 'status', 'starting_hint', 'published_at'])]
class Hunt extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function clues(): HasMany
    {
        return $this->hasMany(Clue::class)->orderBy('order');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}

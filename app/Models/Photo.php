<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['hunt_id', 'nickname', 'caption', 'path', 'hidden_at', 'hidden_by'])]
class Photo extends Model
{
    public function hunt(): BelongsTo
    {
        return $this->belongsTo(Hunt::class);
    }

    public function hiddenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hidden_by');
    }

    public function scopeVisible($query)
    {
        return $query->whereNull('hidden_at');
    }

    protected function casts(): array
    {
        return [
            'hidden_at' => 'datetime',
        ];
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}

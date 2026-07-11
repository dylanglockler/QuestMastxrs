<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['hunt_id', 'order', 'title', 'riddle_text', 'location_note'])]
class Clue extends Model
{
    use HasFactory;

    public function hunt(): BelongsTo
    {
        return $this->belongsTo(Hunt::class);
    }

    public function hints(): HasMany
    {
        return $this->hasMany(Hint::class)->orderBy('order');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}

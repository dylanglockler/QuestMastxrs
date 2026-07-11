<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['clue_id', 'nickname', 'body', 'hidden_at', 'hidden_by'])]
class Message extends Model
{
    use HasFactory;

    public function clue(): BelongsTo
    {
        return $this->belongsTo(Clue::class);
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
}

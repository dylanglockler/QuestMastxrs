<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['clue_id', 'order', 'text'])]
class Hint extends Model
{
    public function clue(): BelongsTo
    {
        return $this->belongsTo(Clue::class);
    }
}

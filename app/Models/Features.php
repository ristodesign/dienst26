<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}

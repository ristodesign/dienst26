<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminGlobalDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function day(): BelongsTo
    {
        return $this->belongsTo(AdminGlobalDay::class, 'global_day_id', 'id');
    }
}

<?php

namespace App\Models\Staff;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'language_id',
        'name',
        'information',
        'location',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'id', 'staff_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}

<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'vendor_id',
        'date',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(StaffContent::class, 'staff_id', 'staff_id');
    }
}

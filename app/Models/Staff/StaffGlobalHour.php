<?php

namespace App\Models\Staff;

use App\Models\Admin\AdminGlobalDay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffGlobalHour extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function day(): BelongsTo
    {
        return $this->belongsTo(StaffGlobalDay::class, 'global_day_id', 'id');
    }

    public function adminDay(): BelongsTo
    {
        return $this->belongsTo(AdminGlobalDay::class, 'global_day_id', 'id');
    }
}

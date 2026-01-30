<?php

namespace App\Models\Services;

use App\Models\Staff\Staff;
use App\Models\Staff\StaffServiceHour;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Services::class, 'service_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceHoureTime(): BelongsTo
    {
        return $this->belongsTo(StaffServiceHour::class, 'service_hour_id', 'id');
    }

    public function serviceContent(): HasMany
    {
        return $this->hasMany(ServiceContent::class, 'service_id', 'service_id');
    }

    public function vendorInfo(): HasMany
    {
        return $this->hasMany(VendorInfo::class, 'vendor_id', 'vendor_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}

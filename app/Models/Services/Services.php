<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Language;
use App\Models\Vendor;
use App\Models\VendorInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'status',
        'price',
        'prev_price',
        'service_image',
        'zoom_meeting',
        'max_person',
        'calendar_status',
        'average_rating',
        'staff_id',
        'latitude',
        'longitude',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function vendorInfo(): BelongsTo
    {
        return $this->belongsTo(VendorInfo::class, 'vendor_id', 'vendor_id');
    }

    public function content(): HasMany
    {
        return $this->hasMany(ServiceContent::class, 'service_id', 'id');
    }

    public function sliderImage(): HasMany
    {
        return $this->hasMany(ServiceImage::class, 'service_id', 'id');
    }

    public function appointment(): HasMany
    {
        return $this->hasMany(ServiceBooking::class, 'service_id', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceReview::class, 'service_id', 'id')
            ->select('id', 'service_id', 'user_id', 'rating', 'comment', 'created_at'); // select needed fields
    }
}

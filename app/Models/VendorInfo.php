<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'language_id',
        'name',
        'shop_name',
        'country',
        'city',
        'state',
        'zip_code',
        'address',
        'details',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'vendor_id', 'vendor_id');
    }
}

<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Vendor;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory;

    protected $fillable = [
        'vendor_id',
        'status',
        'email',
        'phone',
        'image',
        'order_number',
        'allow_login',
        'username',
        'password',
        'is_day',
        'service_add',
        'service_edit',
        'service_delete',
        'time',
        'role',
        'email_status',
        'info_status',
        'phone_status',
    ];

    public function StaffContent(): HasMany
    {
        return $this->hasMany(StaffContent::class, 'staff_id', 'id');
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
}

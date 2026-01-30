<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Services\ServiceBooking;
use App\Models\Services\ServiceReview;
use App\Models\Services\Services;
use App\Models\Staff\Staff;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Model implements AuthenticatableContract
{
    use Authenticatable, HasApiTokens, HasFactory;

    protected $fillable = [
        'photo',
        'email',
        'recived_email',
        'phone',
        'username',
        'password',
        'status',
        'amount',
        'total_appointment',
        'facebook',
        'twitter',
        'linkedin',
        'avg_rating',
        'email_verified_at',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
    ];

    public function getPhoneAttribute($value)
    {
        if ($this->show_phone_number == 1) {
            return $value;
        }

        return null;
    }

    public function getEmailAttribute($value)
    {
        if ($this->show_email_addresss == 1) {
            return $value;
        }

        return null;
    }

    public function vendor_info(): HasOne
    {
        return $this->hasOne(VendorInfo::class, 'vendor_id', 'id');
    }

    public function vendor_infos(): HasMany
    {
        return $this->hasMany(VendorInfo::class);
    }

    // support ticket
    public function support_ticket(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'vendor_id', 'id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function service(): HasMany
    {
        return $this->hasMany(Services::class, 'vendor_id', 'id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function appointment(): HasMany
    {
        return $this->hasMany(ServiceBooking::class);
    }

    public function serviceReview(): HasMany
    {
        return $this->hasMany(ServiceReview::class, 'vendor_id', 'id');
    }
}

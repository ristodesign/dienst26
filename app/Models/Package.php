<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'term',
        'is_trial',
        'trial_days',
        'status',
        'icon',
        'number_of_service_add',
        'number_of_service_image',
        'number_of_appointment',
        'staff_limit',
        'recommended',
        'zoom_meeting_status',
        'calendar_status',
        'custom_features',
        'support_ticket_status',
        'staff_status',
        'whatsapp_manager_status',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}

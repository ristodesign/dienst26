<?php

namespace App\Models;

use App\Models\Services\ServiceBooking;
use App\Models\Services\ServiceReview;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function productOrder(): HasMany
    {
        return $this->hasMany(ProductOrder::class, 'user_id', 'id');
    }

    public function productReview(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'user_id', 'id');
    }

    public function serviceBooking(): HasMany
    {
        return $this->hasMany(ServiceBooking::class, 'user_id', 'id');
    }

    public function serviceReview(): HasMany
    {
        return $this->hasMany(ServiceReview::class, 'user_id', 'id');
    }

    public function fcmTokens(): HasMany
    {
        return $this->hasMany(\App\Models\FcmToken::class);
    }
}

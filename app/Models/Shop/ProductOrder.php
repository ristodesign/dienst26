<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function userInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(ProductPurchaseItem::class, 'product_order_id', 'id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingCharge::class, 'product_shipping_charge_id', 'id');
    }
}

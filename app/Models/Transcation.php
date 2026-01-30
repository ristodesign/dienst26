<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Shop\ProductOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transcation_id',
        'payment_id',
        'transcation_type',
        'user_id',
        'payment_status',
        'payment_method',
        'grand_total',
        'shipping_charge',
        'tax',
        'gateway_type',
        'currency_symbol',
        'currency_symbol_position',
    ];

    // order
    public function order(): BelongsTo
    {
        return $this->belongsTo(ProductOrder::class, 'payment_id', 'id');
    }

    // order
    public function memberships(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'payment_id', 'id');
    }

    // vendor_id
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'user_id', 'id');
    }

    // vendor_id
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Membership;
use App\Models\Vendor;
use App\Models\Withdraw\WithdrawPaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'payment_method', 'id');
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'transaction_id', 'id');
    }
}

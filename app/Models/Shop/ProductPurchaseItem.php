<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseItem extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ProductOrder::class, 'product_order_id', 'id');
    }

    public function productInfo(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

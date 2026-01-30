<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_type',
        'vendor_id',
        'featured_image',
        'slider_images',
        'status',
        'input_type',
        'file',
        'link',
        'stock',
        'current_price',
        'previous_price',
        'average_rating',
        'is_featured',
    ];

    public function content(): HasMany
    {
        return $this->hasMany(ProductContent::class);
    }

    public function purchase(): HasMany
    {
        return $this->hasMany(ProductPurchaseItem::class, 'product_id', 'id');
    }

    public function review(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
}

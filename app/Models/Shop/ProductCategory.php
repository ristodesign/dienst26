<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'name', 'slug', 'status', 'serial_number'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function productContent(): HasMany
    {
        return $this->hasMany(ProductContent::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductContent::class);
    }
}

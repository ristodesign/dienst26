<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'image',
        'mobail_image',
        'slug',
        'language_id',
        'serial_number',
        'status',
        'background_color',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function service_content(): HasMany
    {
        return $this->hasMany(ServiceContent::class, 'category_id', 'id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(ServiceSubCategory::class, 'category_id', 'id');
    }
}

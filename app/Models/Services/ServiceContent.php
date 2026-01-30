<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'language_id',
        'category_id',
        'name',
        'description',
        'slug',
        'meta_keyword',
        'meta_description',
        'features',
        'address',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Services::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}

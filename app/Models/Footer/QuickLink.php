<?php

namespace App\Models\Footer;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'title', 'url', 'serial_number'];

    public function quickLinkLang(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}

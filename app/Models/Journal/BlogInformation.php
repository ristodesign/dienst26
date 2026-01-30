<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogInformation extends Model
{
    use HasFactory;

    protected $table = 'blog_informations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'blog_category_id',
        'blog_id',
        'title',
        'slug',
        'author',
        'content',
        'meta_keywords',
        'meta_description',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'email',
        'subject',
        'description',
        'attachment',
        'status',
        'last_message',
        'admin_id',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'user_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}

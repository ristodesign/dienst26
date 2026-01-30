<?php

namespace App\Models\Withdraw;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawMethodInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'withdraw_payment_method_id',
        'type',
        'label',
        'name',
        'placeholder',
        'required',
        'order_number',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(WithdrawMethodOption::class);
    }
}

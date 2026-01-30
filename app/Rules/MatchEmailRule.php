<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchEmailRule implements ValidationRule
{
    public $personType;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($role)
    {
        // here, $role variable defines whether it is admin or user
        $this->personType = $role;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = false;

        if ($this->personType == 'admin') {
            $admin = Admin::where('email', $value)->first();
            $exists = !is_null($admin);
        } elseif ($this->personType == 'user') {
            $user = User::where('email', $value)->first();
            $exists = !is_null($user);
        } elseif ($this->personType == 'vendor') {
            $user = Vendor::where('email', $value)->first();
            $exists = !is_null($user);
        }

        if (!$exists) {
            $fail('This email does not exist!');
        }
    }
}

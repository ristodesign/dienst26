<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Contracts\Validation\Rule;

class MatchEmailRule implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        if ($this->personType == 'admin') {
            $admin = Admin::where('email', $value)->first();

            if (is_null($admin)) {
                return false;
            } else {
                return true;
            }
        } elseif ($this->personType == 'user') {
            $user = User::where('email', $value)->first();

            if (is_null($user)) {
                return false;
            } else {
                return true;
            }
        } elseif ($this->personType == 'vendor') {
            $user = Vendor::where('email', $value)->first();

            if (is_null($user)) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'This email does not exist!';
    }
}

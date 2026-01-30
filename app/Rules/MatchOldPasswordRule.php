<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MatchOldPasswordRule implements ValidationRule
{
    private $personType;

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
        $passwordMatches = false;

        if ($this->personType == 'admin') {
            $authAdminPass = Auth::guard('admin')->user()->password;
            $passwordMatches = Hash::check($value, $authAdminPass);
        } elseif ($this->personType == 'user') {
            $authUserPass = Auth::guard('web')->user()->password;
            $passwordMatches = Hash::check($value, $authUserPass);
        } elseif ($this->personType == 'vendor') {
            $authUserPass = Auth::guard('vendor')->user()->password;
            $passwordMatches = Hash::check($value, $authUserPass);
        } elseif ($this->personType == 'staff') {
            $authUserPass = Auth::guard('staff')->user()->password;
            $passwordMatches = Hash::check($value, $authUserPass);
        }

        if (!$passwordMatches) {
            $fail(__('Your provided current password does not match!'));
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class ImageMimeTypeRule implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $image = $value;

        if (
            URL::current() == Route::is('admin.advertise.store_advertisement') ||
            URL::current() == Route::is('admin.advertise.update_advertisement') ||
            URL::current() == Route::is('admin.basic_settings.update_login_image') ||
            URL::current() == Route::is('admin.basic_settings.general_settings.update')
        ) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'gif'];
            $errorMessage = 'Only .jpg, .jpeg, .png, .svg and .gif file is allowed.';
        } else {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $errorMessage = 'Only .jpg, .jpeg and .png file is allowed.';
        }

        $fileExtension = $image->getClientOriginalExtension();

        if (!in_array($fileExtension, $allowedExtensions)) {
            $fail($errorMessage);
        }
    }
}

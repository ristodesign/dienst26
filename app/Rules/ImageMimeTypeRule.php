<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class ImageMimeTypeRule implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        $image = $value;

        if (
            URL::current() == Route::is('admin.advertise.store_advertisement') ||
            URL::current() == Route::is('admin.advertise.update_advertisement') ||
            URL::current() == Route::is('admin.basic_settings.update_login_image') ||
            URL::current() == Route::is('admin.basic_settings.general_settings.update')
        ) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'gif'];
        } else {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
        }

        $fileExtension = $image->getClientOriginalExtension();

        if (in_array($fileExtension, $allowedExtensions)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        if (
            URL::current() == Route::is('admin.advertise.store_advertisement') ||
            URL::current() == Route::is('admin.advertise.update_advertisement') ||
            URL::current() == Route::is('admin.basic_settings.update_login_image') ||
            URL::current() == Route::is('admin.basic_settings.general_settings.update')
        ) {
            return 'Only .jpg, .jpeg, .png, .svg and .gif file is allowed.';
        } else {
            return 'Only .jpg, .jpeg and .png file is allowed.';
        }
    }
}

<?php

return [

    'guards' => [
        'sanctum' => [
            'driver' => 'sanctum',
            'provider' => 'users',
            'hash' => false,
        ],

        'sanctum_vendor' => [
            'driver' => 'sanctum',
            'provider' => 'vendors',
            'hash' => false,
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'vendor' => [
            'driver' => 'session',
            'provider' => 'vendors',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staffs',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'vendors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Vendor::class,
        ],

        'staffs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff\Staff::class,
        ],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

];

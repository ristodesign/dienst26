<?php

use Illuminate\Support\Facades\Facade;

return [

    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
        'PaytmWallet' => Anand\LaravelPaytmWallet\Facades\PaytmWallet::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Stripe' => Cartalyst\Stripe\Laravel\Facades\Stripe::class,
    ])->toArray(),

];

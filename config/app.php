<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
    * Laravel Framework Service Providers...
    */

        /*
    * Package Service Providers...
    */
        Barryvdh\DomPDF\ServiceProvider::class,
        Anand\LaravelPaytmWallet\PaytmWalletServiceProvider::class,
        Cartalyst\Stripe\Laravel\StripeServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,

        /*
    * Application Service Providers...
    */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
        'PaytmWallet' => Anand\LaravelPaytmWallet\Facades\PaytmWallet::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Stripe' => Cartalyst\Stripe\Laravel\Facades\Stripe::class,
    ])->toArray(),

];

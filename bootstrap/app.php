<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Barryvdh\DomPDF\ServiceProvider::class,
        \Anand\LaravelPaytmWallet\PaytmWalletServiceProvider::class,
        \Cartalyst\Stripe\Laravel\StripeServiceProvider::class,
        \Laravel\Socialite\SocialiteServiceProvider::class,
        \Maatwebsite\Excel\ExcelServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [

            '/get-model',

            '/shop/purchase-product/razorpay/notify',
            '/shop/purchase-product/flutterwave/notify',
            '/shop/purchase-product/paytm/notify',
            '/services/paytm/payment/notify',
            '/admin/menu-builder/update-menus',
            '/push-notification/store-endpoint',
            'shop/update-cart',

            '/*paytm/payment-status*',
            '/vendor/membership/mercadopago/cancel',
            '/vendor/membership/mercadopago/success',
            '*/vendor/membership/razorpay/success',
            '*/vendor/membership/razorpay/cancel',
            '/vendor/membership/instamojo/cancel',
            '/*flutterwave/success',
            '/vendor/membership/flutterwave/cancel',
            '/vendor/membership/mollie/cancel',

            '/membership/paytm/payment-status*',
            '/membership/mercadopago/cancel',
            '/membership/razorpay/success',
            '/membership/razorpay/cancel',
            '/membership/instamojo/cancel',
            '/membership/flutterwave/success',
            '/membership/flutterwave/cancel',
            '/membership/mollie/cancel',
            '*/iyzico/success', // use for membership buy
            '*/paytabs/success', // use for membership buy
            '*/notify/paytabs', // use for appointment booking
            '*/notify/iyzico', // use for appointment booking
            '*/myfatoorah/callback',

        ]);

        $middleware->throttleApi();

        $middleware->replace(\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, \App\Http\Middleware\PreventRequestsDuringMaintenance::class);

        $middleware->alias([
            'Deactive' => \App\Http\Middleware\Deactive::class,
            'account.status' => \App\Http\Middleware\PreventRequestsForDeactivatedAccount::class,
            'adminlang' => \App\Http\Middleware\AdminLangMiddleware::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'certificate.status' => \App\Http\Middleware\EnsureCertificateIsEnable::class,
            'change.lang' => \App\Http\Middleware\ChangeLanguage::class,
            'checkPackage' => \App\Http\Middleware\CheckPackage::class,
            'exists.down' => \App\Http\Middleware\RedirectIfDownFileExists::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'limitCheck' => \App\Http\Middleware\LimitCheckMiddleware::class,
            'permission' => \App\Http\Middleware\HasPermission::class,
            'shop.status' => \App\Http\Middleware\ShopStatusCheck::class,
            'staffCheck' => \App\Http\Middleware\StaffPermission::class,
            'stafflang' => \App\Http\Middleware\StaffLangMiddleware::class,
            'vendorlang' => \App\Http\Middleware\VendorLangMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

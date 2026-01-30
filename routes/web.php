<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\FrontEnd;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

// cron job for sending expiry mail
Route::get('/subcheck', [CronJobController::class, 'expired'])->name('cron.expired');
Route::get('/check-payment', [CronJobController::class, 'check_payment'])->name('cron.check_payment');

Route::get('/change-language', [FrontEnd\MiscellaneousController::class, 'changeLanguage'])->name('change_language');

Route::post('/store-subscriber', [FrontEnd\MiscellaneousController::class, 'storeSubscriber'])->name('store_subscriber');

Route::get('/offline', [FrontEnd\HomeController::class, 'offline'])->middleware('change.lang');
Route::post('/send-whatsapp', [WhatsAppController::class, 'sendMessage'])->name('send.whatsapp.message');

Route::middleware('change.lang')->group(function () {
    Route::get('/', [FrontEnd\HomeController::class, 'index'])->name('index');

    // services route
    Route::prefix('services')->group(function () {
        Route::get('/', [FrontEnd\Services\ServiceController::class, 'index'])->name('frontend.services');

        Route::get('addto/wishlist/{id}', [FrontEnd\UserController::class, 'add_to_wishlist'])->name('addto.wishlist');
        Route::get('remove/wishlist/{id}', [FrontEnd\UserController::class, 'remove_wishlist'])->name('remove.wishlist');
        // service search
        Route::get('service/search', [FrontEnd\Services\ServiceController::class, 'searchService'])->name('frontend.services.category.search');

        // service rating
        Route::post('service/store-review/{id}', [FrontEnd\Services\ServiceController::class, 'storeReview'])->name('frontend.service.rating.store');

        Route::post('contact/message', [FrontEnd\Services\ServiceController::class, 'message'])->name('frontend.services.contact.message');

        Route::get('/details/{slug}/{id}', [FrontEnd\Services\ServiceController::class, 'details'])->name('frontend.service.details');

        Route::get('services-staff-content/{id}', [FrontEnd\Services\ServiceController::class, 'staffcontent'])->name('frontend.service.content');

        Route::get('billing-form', [FrontEnd\Services\ServiceController::class, 'billing'])->name('frontend.services.billing');

        Route::get('payment-success/{id}', [FrontEnd\Services\ServiceController::class, 'paymentSuccess'])->name('frontend.service.payment.success');

        // show time slot on modal
        Route::get('show-staff-hour/{id}', [FrontEnd\Services\ServiceController::class, 'staffHour'])->name('frontend.staff.hour');

        Route::get('staff-date-time/{id}', [FrontEnd\Services\ServiceController::class, 'staffHoliday'])->name('frontend.staff.holiday');

        Route::post('login', [FrontEnd\Services\ServiceController::class, 'login'])->name('frontend.user.login');

        Route::get('staff/search/{id}', [FrontEnd\Services\ServiceController::class, 'staffSearch'])->name('frontend.staff.search');

        Route::post('session/forget', [FrontEnd\Services\ServiceController::class, 'sessionForget'])->name('service.session.forget');

        Route::post('payment/process/', [FrontEnd\Booking\ServicePaymentController::class, 'index'])->name('frontend.service.payment');

        // service booking payment notify route
        Route::prefix('/payment/notify')->group(function () {
            Route::get('/paypal', [FrontEnd\Booking\Payment\PayPalController::class, 'notify'])->name('frontend.service_booking.paypal.notify');
            Route::post('/razorpay', [FrontEnd\Booking\Payment\RazorpayController::class, 'notify'])->name('frontend.service_booking.razorpay.notify');
            Route::get('/flutterwave', [FrontEnd\Booking\Payment\FlutterwaveController::class, 'notify'])->name('frontend.service_booking.flutterwave.notify');
            Route::get('/instamojo', [FrontEnd\Booking\Payment\InstamojoController::class, 'notify'])->name('frontend.service_booking.instamojo.notify');
            Route::get('/mollie', [FrontEnd\Booking\Payment\MollieController::class, 'notify'])->name('frontend.service_booking.mollie.notify');
            Route::get('/paystack', [FrontEnd\Booking\Payment\PaystackController::class, 'notify'])->name('frontend.service_booking.paystack.notify');
            Route::get('/mercadopago', [FrontEnd\Booking\Payment\MercadoPagoController::class, 'notify'])->name('frontend.service_booking.mercadopago.notify');
            Route::post('/paytm', [FrontEnd\Booking\Payment\PaytmController::class, 'notify'])->name('frontend.service_booking.paytm.notify');
            Route::any('myfatoorah/callback', [FrontEnd\Booking\Payment\MyFatoorahController::class, 'notify'])->name('frontend.service_booking.myfatoorah_notify');
            Route::any('phonepe', [FrontEnd\Booking\Payment\PhonepeController::class, 'notify'])->name('frontend.service_booking.phonepe_notify');
            Route::get('xendit', [FrontEnd\Booking\Payment\XenditController::class, 'notify'])->name('frontend.service_booking.xendit_notify');
            Route::get('midtrans', [FrontEnd\Booking\Payment\MidtransController::class, 'notify'])->name('frontend.service_booking.midtrans_notify');
            Route::get('toyyibpay', [FrontEnd\Booking\Payment\ToyyibpayController::class, 'notify'])->name('frontend.service_booking.toyyibpay_notify');
            Route::post('paytabs', [FrontEnd\Booking\Payment\PaytabsController::class, 'notify'])->name('frontend.service_booking.paytabs_notify');
            Route::get('perfectmoney', [FrontEnd\Booking\Payment\PerfectMoneyController::class, 'notify'])->name('frontend.service_booking.perfectmoney_notify');
            Route::post('iyzico', [FrontEnd\Booking\Payment\IyzicoController::class, 'notify'])->name('frontend.service_booking.iyzico_notify');
        });
        Route::get('/booking/complete/popup', [FrontEnd\Booking\ServicePaymentController::class, 'complete'])->name('frontend.service.booking.complete');
        Route::get('/cancel', [FrontEnd\Booking\ServicePaymentController::class, 'cancel'])->name('frontend.service_booking.cancel');
    });

    // products routes are goes here
    Route::get('/products', [FrontEnd\Shop\ProductController::class, 'index'])->name('shop.products')->middleware('shop.status');

    Route::prefix('/product')->middleware(['shop.status'])->group(function () {
        Route::get('/{slug}', [FrontEnd\Shop\ProductController::class, 'show'])->name('shop.product_details');

        Route::get('/{id}/add-to-cart/{quantity}', [FrontEnd\Shop\ProductController::class, 'addToCart'])->name('shop.product.add_to_cart');
    });

    Route::prefix('/shop')->middleware(['shop.status'])->group(function () {
        Route::get('/cart', [FrontEnd\Shop\ProductController::class, 'cart'])->name('shop.cart');

        Route::post('/update-cart', [FrontEnd\Shop\ProductController::class, 'updateCart'])->name('shop.update_cart');

        Route::get('/cart/remove-product/{id}', [FrontEnd\Shop\ProductController::class, 'removeProduct'])->name('shop.cart.remove_product');

        Route::get('put-shipping-method-id/{id}', [FrontEnd\Shop\ProductController::class, 'put_shipping_method'])->name('put-shipping-method-id');

        Route::prefix('/checkout')->group(function () {
            Route::get('', [FrontEnd\Shop\ProductController::class, 'checkout'])->name('shop.checkout');

            Route::post('/apply-coupon', [FrontEnd\Shop\ProductController::class, 'applyCoupon']);

            Route::get('/offline-gateway/{id}/check-attachment', [FrontEnd\Shop\ProductController::class, 'checkAttachment']);
        });

        Route::prefix('/purchase-product')->group(function () {
            Route::post('', [FrontEnd\Shop\PurchaseProcessController::class, 'index'])->name('shop.purchase_product');
            Route::get('/paypal/success', [FrontEnd\PaymentGateway\PayPalController::class, 'notify'])->name('shop.purchase_product.paypal.notify');
            Route::get('/instamojo/success', [FrontEnd\PaymentGateway\InstamojoController::class, 'notify'])->name('shop.purchase_product.instamojo.notify');
            Route::get('/paystack/success', [FrontEnd\PaymentGateway\PaystackController::class, 'notify'])->name('shop.purchase_product.paystack.notify');
            Route::get('/flutterwave/success', [FrontEnd\PaymentGateway\FlutterwaveController::class, 'notify'])->name('shop.purchase_product.flutterwave.notify');
            Route::post('/razorpay/success', [FrontEnd\PaymentGateway\RazorpayController::class, 'notify'])->name('shop.purchase_product.razorpay.notify');
            Route::get('/mercadopago/success', [FrontEnd\PaymentGateway\MercadoPagoController::class, 'notify'])->name('shop.purchase_product.mercadopago.notify');
            Route::get('/mollie/success', [FrontEnd\PaymentGateway\MollieController::class, 'notify'])->name('shop.purchase_product.mollie.notify');
            Route::post('/paytm/success', [FrontEnd\PaymentGateway\PaytmController::class, 'notify'])->name('shop.purchase_product.paytm.notify');
            Route::get('/myfatoorah/success', [FrontEnd\PaymentGateway\MyFatoorahController::class, 'notify'])->name('shop.purchase_product.myfatoorah.notify');
            Route::get('/yoco/success', [FrontEnd\PaymentGateway\YocoController::class, 'notify'])->name('shop.purchase_product.yoco.notify');
            Route::get('/xendit/success', [FrontEnd\PaymentGateway\XenditController::class, 'notify'])->name('shop.purchase_product.xendit.notify');
            Route::get('/toyyibpay/success', [FrontEnd\PaymentGateway\ToyyibpayController::class, 'notify'])->name('shop.purchase_product.toyyibpay.notify');
            Route::get('/phonepe/success', [FrontEnd\PaymentGateway\PhonepeController::class, 'notify'])->name('shop.purchase_product.phonepe.notify');
            Route::post('/paytabs/success', [FrontEnd\PaymentGateway\PaytabsController::class, 'notify'])->name('shop.purchase_product.paytabs.notify');
            Route::any('/midtrans/success', [FrontEnd\PaymentGateway\MidtransController::class, 'notify'])->name('shop.purchase_product.midtrans.notify');
            Route::post('/iyzico/success', [FrontEnd\PaymentGateway\IyzicoController::class, 'notify'])->name('shop.purchase_product.iyzico.notify');
            Route::get('/complete/{type?}', [FrontEnd\Shop\PurchaseProcessController::class, 'complete'])->name('shop.purchase_product.complete')->middleware('change.lang');
            Route::get('/cancel', [FrontEnd\Shop\PurchaseProcessController::class, 'cancel'])->name('shop.purchase_product.cancel');
        });

        Route::post('/product/{id}/store-review', [FrontEnd\Shop\ProductController::class, 'storeReview'])->name('shop.product_details.store_review');
    });

    Route::prefix('pricing')->group(function () {
        Route::get('/', [FrontEnd\PricingController::class, 'index'])->name('frontend.pricing');
    });

    Route::prefix('vendors')->group(function () {
        Route::get('/', [FrontEnd\VendorController::class, 'index'])->name('frontend.vendors');
        Route::post('contact/message', [FrontEnd\VendorController::class, 'contact'])->name('vendor.contact.message');
    });
    Route::get('vendor/{username}', [FrontEnd\VendorController::class, 'details'])->name('frontend.vendor.details');

    Route::prefix('/blog')->group(function () {
        Route::get('', [FrontEnd\BlogController::class, 'index'])->name('blog');

        Route::get('/{slug}', [FrontEnd\BlogController::class, 'show'])->name('blog_details');
    });

    Route::get('/faq', [FrontEnd\FaqController::class, 'faq'])->name('faq');
    Route::get('/about-us', [FrontEnd\HomeController::class, 'about'])->name('about_us');

    Route::prefix('/contact')->group(function () {
        Route::get('', [FrontEnd\ContactController::class, 'contact'])->name('contact');

        Route::post('/send-mail', [FrontEnd\ContactController::class, 'sendMail'])->name('contact.send_mail');
    });
});

Route::post('/advertisement/{id}/count-view', [FrontEnd\MiscellaneousController::class, 'countAdView']);

Route::prefix('login')->middleware(['guest:web', 'change.lang'])->group(function () {
    // user login via facebook route
    Route::prefix('/user/facebook')->group(function () {
        Route::get('', [FrontEnd\UserController::class, 'redirectToFacebook'])->name('user.login.facebook');

        Route::get('/callback', [FrontEnd\UserController::class, 'handleFacebookCallback']);
    });

    // user login via google route
    Route::prefix('/google')->group(function () {
        Route::get('', [FrontEnd\UserController::class, 'redirectToGoogle'])->name('user.login.google');

        Route::get('/callback', [FrontEnd\UserController::class, 'handleGoogleCallback']);
    });
});

Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
    Route::prefix('/login')->group(function () {
        // user redirect to login page route
        Route::get('', [FrontEnd\UserController::class, 'login'])->name('user.login');
    });
    // user login submit route
    Route::post('/login-submit', [FrontEnd\UserController::class, 'loginSubmit'])->name('user.login_submit');

    // user forget password route
    Route::get('/forget-password', [FrontEnd\UserController::class, 'forgetPassword'])->name('user.forget_password');

    // send mail to user for forget password route
    Route::post('/send-forget-password-mail', [FrontEnd\UserController::class, 'forgetPasswordMail'])->name('user.send_forget_password_mail');

    // reset password route
    Route::get('/reset-password', [FrontEnd\UserController::class, 'resetPassword']);

    // user reset password submit route
    Route::post('/reset-password-submit', [FrontEnd\UserController::class, 'resetPasswordSubmit']);

    // user redirect to signup page route
    Route::get('/signup', [FrontEnd\UserController::class, 'signup'])->name('user.signup');

    // user signup submit route
    Route::post('/signup-submit', [FrontEnd\UserController::class, 'signupSubmit'])->name('user.signup_submit');

    // signup verify route
    Route::get('/signup-verify/{token}', [FrontEnd\UserController::class, 'signupVerify'])->withoutMiddleware('change.lang');
});

Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
    // user redirect to dashboard route
    Route::get('/dashboard', [FrontEnd\UserController::class, 'redirectToDashboard'])->name('user.dashboard');
    Route::get('/wishlist', [FrontEnd\UserController::class, 'wishlist'])->name('user.wishlist');

    Route::get('appointment', [FrontEnd\AppointmentController::class, 'appointment'])->name('user.appointment.index');
    Route::get('appointment/details/{id}', [FrontEnd\AppointmentController::class, 'details'])->name('user.appointment.details');

    Route::get('order', [FrontEnd\OrderController::class, 'index'])->name('user.order.index')->middleware('shop.status');
    Route::get('/order/details/{id}', [FrontEnd\OrderController::class, 'details'])->name('user.order.details')->middleware('shop.status');

    Route::post('download/{product_id}', [FrontEnd\OrderController::class, 'download'])->name('user.product_order.product.download')->middleware('shop.status');

    // edit profile route
    Route::get('/edit-profile', [FrontEnd\UserController::class, 'editProfile'])->name('user.edit_profile');

    // update profile route
    Route::post('/update-profile', [FrontEnd\UserController::class, 'updateProfile'])->name('user.update_profile');

    // change password route
    Route::get('/change-password', [FrontEnd\UserController::class, 'changePassword'])->name('user.change_password');

    // update password route
    Route::post('/update-password', [FrontEnd\UserController::class, 'updatePassword'])->name('user.update_password')->withoutMiddleware('change.lang');

    // user logout attempt route
    Route::get('/logout', [FrontEnd\UserController::class, 'logoutSubmit'])->name('user.logout')->withoutMiddleware('change.lang');
});

// service unavailable route
Route::get('/service-unavailable', [FrontEnd\MiscellaneousController::class, 'serviceUnavailable'])->name('service_unavailable')->middleware('exists.down');

/*
|--------------------------------------------------------------------------
| admin frontend route
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
    // admin redirect to login page route
    Route::get('/', [Admin\AdminController::class, 'login'])->name('admin.login');

    // admin login attempt route
    Route::post('/auth', [Admin\AdminController::class, 'authentication'])->name('admin.auth');

    // admin forget password route
    Route::get('/forget-password', [Admin\AdminController::class, 'forgetPassword'])->name('admin.forget_password');

    // send mail to admin for forget password route
    Route::post('/mail-for-forget-password', [Admin\AdminController::class, 'forgetPasswordMail'])->name('admin.mail_for_forget_password');
});

/*
|--------------------------------------------------------------------------
| Additional Route Files (web middleware)
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';
require __DIR__.'/vendor.php';
require __DIR__.'/staff.php';

/*
|--------------------------------------------------------------------------
| Custom Page Route For UI
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [FrontEnd\PageController::class, 'page'])->name('dynamic_page')->middleware('change.lang');

// fallback route
Route::fallback(function () {
    return view('errors.404');
})->middleware('change.lang');

<?php

use App\Http\Controllers\Front;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| vendor Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware('change.lang')->group(function () {
    Route::get('/dashboard', [Vendor\VendorController::class, 'index'])->name('vendor.index');
    Route::get('/signup', [Vendor\VendorController::class, 'signup'])->name('vendor.signup');
    Route::post('/signup/submit', [Vendor\VendorController::class, 'create'])->name('vendor.signup_submit');
    Route::get('/login', [Vendor\VendorController::class, 'login'])->name('vendor.login')->middleware('guest:vendor');
    Route::post('/login/submit', [Vendor\VendorController::class, 'authentication'])->name('vendor.login_submit');

    Route::get('/email/verify', [Vendor\VendorController::class, 'confirm_email']);

    Route::get('/forget-password', [Vendor\VendorController::class, 'forget_passord'])->name('vendor.forget.password');
    Route::post('/send-forget-mail', [Vendor\VendorController::class, 'forget_mail'])->name('vendor.forget.mail');
    Route::get('/reset-password', [Vendor\VendorController::class, 'reset_password'])->name('vendor.reset.password');
    Route::post('/update-forget-password', [Vendor\VendorController::class, 'update_password'])->name('vendor.update-forget-password');
});

Route::prefix('vendor')->middleware('auth:vendor', 'Deactive', 'vendorlang')->group(function () {
    // language change in admin dashboard
    Route::get('/change-language/{lang}', [Vendor\VendorController::class, 'languageChange'])->name('vendor.language.change');
    Route::get('dashboard', [Vendor\VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/change-password', [Vendor\VendorController::class, 'change_password'])->name('vendor.change_password');
    Route::post('/update-password', [Vendor\VendorController::class, 'updated_password'])->name('vendor.update_password');
    Route::get('/edit-profile', [Vendor\VendorController::class, 'edit_profile'])->name('vendor.edit.profile');
    Route::post('/profile/update', [Vendor\VendorController::class, 'update_profile'])->name('vendor.update_profile');
    Route::get('/logout', [Vendor\VendorController::class, 'logout'])->name('vendor.logout');

    // change vendor-panel theme (dark/light) route
    Route::post('/change-theme', [Vendor\VendorController::class, 'changeTheme'])->name('vendor.change_theme');
    Route::get('/subscription-log', [Vendor\VendorController::class, 'subscription_log'])->name('vendor.subscription_log');

    // vendor package extend route
    Route::get('/package-list', [Vendor\BuyPlanController::class, 'index'])->name('vendor.plan.extend.index');
    Route::get('/package/checkout/{package_id}', [Vendor\BuyPlanController::class, 'checkout'])->name('vendor.plan.extend.checkout');
    Route::post('/package/checkout', [Vendor\VendorCheckoutController::class, 'checkout'])->name('vendor.plan.checkout');

    Route::post('/payment/instructions', [Vendor\VendorCheckoutController::class, 'paymentInstruction'])->name('vendor.payment.instructions');

    // checkout payment gateway routes
    Route::prefix('membership')->group(function () {
        Route::get('paypal/success', [Payment\PaypalController::class, 'successPayment'])->name('membership.paypal.success');
        Route::get('paypal/cancel', [Payment\PaypalController::class, 'cancelPayment'])->name('membership.paypal.cancel');
        Route::get('stripe/cancel', [Payment\StripeController::class, 'cancelPayment'])->name('membership.stripe.cancel');
        Route::post('paytm/payment-status', [Payment\PaytmController::class, 'paymentStatus'])->name('membership.paytm.status');
        Route::get('paystack/success', [Payment\PaystackController::class, 'successPayment'])->name('membership.paystack.success');
        Route::post('mercadopago/cancel', [Payment\paymenMercadopagoController::class, 'cancelPayment'])->name('membership.mercadopago.cancel');

        Route::post('iyzico/success', [Payment\IyzicoController::class, 'successPayment'])->name('membership.iyzico.success');
        Route::get('iyzico/cancel', [Payment\IyzicoController::class, 'cancelPayment'])->name('membership.iyzico.cancel');

        Route::get('xendit/success', [Payment\XenditController::class, 'successPayment'])->name('membership.xendit.success');
        Route::get('xendit/cancel', [Payment\XenditController::class, 'cancelPayment'])->name('membership.xendit.cancel');

        Route::post('paytabs/success', [Payment\PaytabsController::class, 'successPayment'])->name('membership.paytab.success');
        Route::get('paytabs/cancel', [Payment\PaytabsController::class, 'cancelPayment'])->name('membership.paytab.cancel');

        Route::any('phonepe/success', [Payment\PhonePeController::class, 'successPayment'])->name('membership.phonepe.success');
        Route::get('phonepe/cancel', [Payment\PhonePeController::class, 'cancelPayment'])->name('membership.phonepe.cancel');

        Route::any('myfatoorah/callback', [Payment\MyFatoorahController::class, 'successPayment'])->name('membership.myfatoorah.success');
        Route::get('myfatoorah/cancel', [Payment\MyFatoorahController::class, 'cancelPayment'])->name('membership.myfatoorah.cancel');

        Route::get('toyyibpay/success', [Payment\ToyyibpayController::class, 'successPayment'])->name('membership.toyyibpay.success');
        Route::get('toyyibpay/cancel', [Payment\ToyyibpayController::class, 'cancelPayment'])->name('membership.toyyibpay.cancel');

        Route::get('yoco/success', [Payment\YocoController::class, 'successPayment'])->name('membership.yoco.success');
        Route::get('yoco/cancel', [Payment\YocoController::class, 'cancelPayment'])->name('membership.yoco.cancel');

        Route::get('midtrans/success', [Payment\MidtransController::class, 'successPayment'])->name('membership.midtrans.success');
        Route::get('midtrans/cancel', [Payment\MidtransController::class, 'cancelPayment'])->name('membership.midtrans.cancel');

        Route::get('perfectmoney/success', [Payment\PerfectMoneyController::class, 'successPayment'])->name('membership.perfectmoney.success');
        Route::get('perfectmoney/cancel', [Payment\PerfectMoneyController::class, 'cancelPayment'])->name('membership.perfectmoney.cancel');

        Route::get('mercadopago/success', [Payment\MercadopagoController::class, 'successPayment'])->name('membership.mercadopago.success');
        Route::post('razorpay/success', [Payment\RazorpayController::class, 'successPayment'])->name('membership.razorpay.success');
        Route::post('razorpay/cancel', [Payment\RazorpayController::class, 'cancelPayment'])->name('membership.razorpay.cancel');
        Route::get('instamojo/success', [Payment\InstamojoController::class, 'successPayment'])->name('membership.instamojo.success');
        Route::post('instamojo/cancel', [Payment\InstamojoController::class, 'cancelPayment'])->name('membership.instamojo.cancel');
        Route::post('flutterwave/success', [Payment\FlutterWaveController::class, 'successPayment'])->name('membership.flutterwave.success');
        Route::post('flutterwave/cancel', [Payment\FlutterWaveController::class, 'cancelPayment'])->name('membership.flutterwave.cancel');
        Route::get('/mollie/success', [Payment\MollieController::class, 'successPayment'])->name('membership.mollie.success');
        Route::post('mollie/cancel', [Payment\MollieController::class, 'cancelPayment'])->name('membership.mollie.cancel');
        Route::get('anet/cancel', [Payment\AuthorizeController::class, 'cancelPayment'])->name('membership.anet.cancel');
        Route::get('/offline/success', [Front\CheckoutController::class, 'offlineSuccess'])->name('membership.offline.success');
        Route::get('/trial/success', [Front\CheckoutController::class, 'trialSuccess'])->name('membership.trial.success');

        Route::get('/online/success', [Vendor\VendorCheckoutController::class, 'onlineSuccess'])->name('success.page');
    });

    // shipping-method route
    Route::get('/shipping-methods', [Vendor\VendorController::class, 'methodSettings'])->name('vendor.equipment_booking.settings.shipping_methods');

    Route::post('/update-method-settings', [Vendor\VendorController::class, 'updateMethodSettings'])->name('vendor.equipment_booking.settings.update_method_settings');

    Route::prefix('withdraw')->group(function () {
        Route::get('/', [Vendor\VendorWithdrawController::class, 'index'])->name('vendor.withdraw');
        Route::get('/create', [Vendor\VendorWithdrawController::class, 'create'])->name('vendor.withdraw.create');
        Route::get('/get-method/input/{id}', [Vendor\VendorWithdrawController::class, 'get_inputs']);

        Route::get('/balance-calculation/{method}/{amount}', [Vendor\VendorWithdrawController::class, 'balance_calculation']);

        Route::post('/send-request', [Vendor\VendorWithdrawController::class, 'send_request'])->name('vendor.withdraw.send-request');
        Route::post('/witdraw/bulk-delete', [Vendor\VendorWithdrawController::class, 'bulkDelete'])->name('vendor.witdraw.bulk_delete_withdraw');
        Route::post('/witdraw/delete', [Vendor\VendorWithdrawController::class, 'Delete'])->name('vendor.witdraw.delete_withdraw');
    });

    Route::get('/transcation', [Vendor\VendorController::class, 'transcation'])->name('vendor.transcation');
    Route::post('/transcation/delete', [Vendor\VendorController::class, 'destroy'])->name('vendor.transcation.delete');
    Route::post('/transcation/bulk-delete', [Vendor\VendorController::class, 'bulk_destroy'])->name('vendor.transcation.bulk_delete');

    // ====support tickets ============
    Route::get('support/ticket/create', [Vendor\SupportTicketController::class, 'create'])->name('vendor.support_ticket.create');
    Route::post('support/ticket/store', [Vendor\SupportTicketController::class, 'store'])->name('vendor.support_ticket.store')->middleware('limitCheck:service,update,downgrade');
    Route::get('support/tickets', [Vendor\SupportTicketController::class, 'index'])->name('vendor.support_tickets');
    Route::get('support/message/{id}', [Vendor\SupportTicketController::class, 'message'])->name('vendor.support_tickets.message');
    Route::post('support-ticket/zip-upload', [Vendor\SupportTicketController::class, 'zip_file_upload'])->name('vendor.support_ticket.zip_file.upload');
    Route::post('support-ticket/reply/{id}', [Vendor\SupportTicketController::class, 'ticketreply'])->name('vendor.support_ticket.reply');

    Route::post('support-ticket/delete/{id}', [Vendor\SupportTicketController::class, 'delete'])->name('vendor.support_tickets.delete');

    // service managment route
    Route::prefix('service-management')->group(function () {
        Route::get('/', [Vendor\Services\ServiceController::class, 'index'])->name('vendor.service_managment');

        Route::get('create', [Vendor\Services\ServiceController::class, 'create'])->name('vendor.service_managment.create');
        Route::post('store', [Vendor\Services\ServiceController::class, 'store'])->name('vendor.service_managment.store')->middleware('limitCheck:service,store');
        Route::get('get-subcategory/{category_id}', [Vendor\Services\ServiceController::class, 'getSucategory'])->name('vendor.service_managment.get_subcategory');

        // service slider image
        Route::post('/img-store', [Vendor\Services\ServiceController::class, 'imagesstore'])->name('vendor.service.imagesstore');
        Route::post('/img-remove', [Vendor\Services\ServiceController::class, 'removeImage'])->name('vendor.service.imagermv');
        Route::post('/img-db-remove', [Vendor\Services\ServiceController::class, 'imagedbrmv'])->name('vendor.service.imgdbrmv');
        Route::get('delete/slider/image', [Vendor\Services\ServiceController::class, 'deleteSliderImage'])->name('vendor.service.slider.delete');

        Route::get('edit/{id}', [Vendor\Services\ServiceController::class, 'edit'])->name('vendor.service_managment.edit');

        Route::post('update/{id}', [Vendor\Services\ServiceController::class, 'update'])->name('vendor.service_managment.update')->middleware('limitCheck:service,update');

        Route::post('delete/{id}', [Vendor\Services\ServiceController::class, 'destroy'])->name('vendor.service_managment.delete_product');

        Route::post('/bulk-delete-services', [Vendor\Services\ServiceController::class, 'bulkDestroy'])->name('vendor.service_managment.bulk_delete');

        Route::post('service-status', [Vendor\Services\ServiceController::class, 'servicestatus'])->name('vendor.service.status.change')->middleware('limitCheck:service,update,downgrade');

        // featured service payment success message
        Route::get('/online/success', [Vendor\Services\ServiceController::class, 'onlineSuccess'])->name('featured.service.online.success.page');

        Route::get('/offline/success', [Vendor\Services\ServiceController::class, 'offlineSuccess'])->name('featured.service.offline.success.page');

        Route::get('featured/payment/cancel', [Vendor\ServicePromotion\ServicePromotionController::class, 'cancel'])->name('vendor.featured.cancel');
    });

    // service promotion
    Route::prefix('payment/process')->group(function () {
        Route::post('/', [Vendor\ServicePromotion\ServicePromotionController::class, 'index'])->name('vendor.service.payment');
        Route::get('/paypal/success', [Vendor\ServicePromotion\Payment\PayPalController::class, 'notify'])->name('vendor.featured.paypal.notify');
        Route::get('/flutterwave/success', [Vendor\ServicePromotion\Payment\FlutterwaveController::class, 'notify'])->name('vendor.featured.flutterwave.notify');
        Route::post('/razorpay/success', [Vendor\ServicePromotion\Payment\RazorpayController::class, 'notify'])->name('vendor.featured.razorpay.notify');
        Route::get('/mollie/success', [Vendor\ServicePromotion\Payment\MollieController::class, 'notify'])->name('vendor.featured.mollie.notify');
        Route::get('/instamojo/success', [Vendor\ServicePromotion\Payment\InstamojoController::class, 'notify'])->name('vendor.featured.instamojo.notify');
        Route::get('/mercadopago/success', [Vendor\ServicePromotion\Payment\MercadoPagoController::class, 'notify'])->name('vendor.featured.mercadopago.notify');
        Route::get('/paystack/success', [Vendor\ServicePromotion\Payment\PaystackController::class, 'notify'])->name('vendor.featured.paystack.notify');
        Route::post('/paytm/success', [Vendor\ServicePromotion\Payment\PaytmController::class, 'notify'])->name('vendor.featured.paytm.notify');
        Route::post('/iyzico/success', [Vendor\ServicePromotion\Payment\IyzicoController::class, 'notify'])->name('vendor.featured.iyzico.notify');
        Route::get('/midtrans/success', [Vendor\ServicePromotion\Payment\MidtransController::class, 'notify'])->name('vendor.featured.midtrans.notify');
        Route::get('/myfatoorah/success', [Vendor\ServicePromotion\Payment\MyFatoorahController::class, 'notify'])->name('vendor.featured.myfatoorah.notify');
        Route::get('/perfectmoney/success', [Vendor\ServicePromotion\Payment\PerfectMoneyController::class, 'notify'])->name('vendor.featured.perfectmoney.notify');
        Route::any('/phonepe/success', [Vendor\ServicePromotion\Payment\PhonePeController::class, 'notify'])->name('vendor.featured.phonepe.notify');
        Route::get('/toyyibpay/success', [Vendor\ServicePromotion\Payment\ToyyibpayController::class, 'notify'])->name('vendor.featured.toyyibpay.notify');
        Route::get('/xendit/success', [Vendor\ServicePromotion\Payment\XenditController::class, 'notify'])->name('vendor.featured.xendit.notify');
        Route::get('/yoco/success', [Vendor\ServicePromotion\Payment\YocoController::class, 'notify'])->name('vendor.featured.yoco.notify');
        Route::post('/paytabs/success', [Vendor\ServicePromotion\Payment\PaytabsController::class, 'notify'])->name('vendor.featured.paytabs.notify');
    });

    // Staff Managment Route
    Route::prefix('staff-managment')->group(function () {
        Route::get('/', [Vendor\Staff\StaffController::class, 'index'])->name('vendor.staff_managment');
        Route::get('create', [Vendor\Staff\StaffController::class, 'create'])->name('vendor.staff_managment.create');
        Route::post('store', [Vendor\Staff\StaffController::class, 'store'])->name('vendor.staff_managment.store')->middleware('limitCheck:staff,store');
        Route::get('edit/{id}', [Vendor\Staff\StaffController::class, 'edit'])->name('vendor.staff_managment.edit');
        Route::post('update/{id}', [Vendor\Staff\StaffController::class, 'update'])->name('vendor.staff_managment.update')->middleware('limitCheck:staff,update');
        Route::delete('delete/{id}', [Vendor\Staff\StaffController::class, 'destroy'])->name('vendor.staff_managment.delete');
        Route::post('staff/bulkDestroy', [Vendor\Staff\StaffController::class, 'bulkDestroy'])->name('vendor.staff_managment.bulkDestroy');
        Route::post('staff-status', [Vendor\Staff\StaffController::class, 'staffstatus'])->name('vendor.status.change')->middleware('limitCheck:service,update,downgrade');
        Route::get('/secret-login/{id}', [Vendor\Staff\StaffController::class, 'secret_login'])->name('vendor.staff.secret-login');
        Route::get('/permission/{id}', [Vendor\Staff\StaffController::class, 'permission'])->name('vendor.staff.permission');
        Route::post('/permission-update/{id}', [Vendor\Staff\StaffController::class, 'permissionUpdate'])->name('vendor.staff.permission_update');
        Route::get('/change-password/{id}', [Vendor\Staff\StaffController::class, 'changePassword'])->name('vendor.staff.change_password');
        Route::post('/update-password/{id}', [Vendor\Staff\StaffController::class, 'updatePassword'])->name('vendor.staff.update_password');

        // staff time slot route
        Route::prefix('staff')->group(function () {
            Route::get('/days/{staff_id}', [Vendor\Staff\StaffServiceHourController::class, 'day'])->name('vendor.service.day');

            Route::get('/time-slots', [Vendor\Staff\StaffServiceHourController::class, 'index'])->name('vendor.time-slot.manage');

            Route::post('/time-slots/store', [Vendor\Staff\StaffServiceHourController::class, 'store'])->name('vendor.service-hour.store')->middleware('limitCheck:service,update');

            Route::post('/time-slots/update', [Vendor\Staff\StaffServiceHourController::class, 'update'])->name('vendor.service-hour.update')->middleware('limitCheck:service,update');

            Route::post('/time-slots/destroy/{id}', [Vendor\Staff\StaffServiceHourController::class, 'destroy'])->name('vendor.service-houre.destroy');

            Route::post('/time-slots/bulk-delete', [Vendor\Staff\StaffServiceHourController::class, 'bulkDestroy'])->name('vendor.service-hour.bulk_delete');

            Route::post('change-weekend/{id}', [Vendor\Staff\StaffServiceHourController::class, 'weekendChange'])->name('vendor.staff.change.weekend')->middleware('limitCheck:service,update,downgrade');
        });

        // staff holiday route
        Route::prefix('staff-holiday')->group(function () {
            Route::get('index/{id}', [Vendor\Staff\StaffHolidayController::class, 'index'])->name('vendor.staff.holiday.index');

            Route::post('customize/status/change/{id}', [Vendor\Staff\StaffHolidayController::class, 'changeStaffSetting'])->name('vendor.customize.status.change')->middleware('limitCheck:service,update,downgrade');

            Route::post('store', [Vendor\Staff\StaffHolidayController::class, 'store'])->name('vendor.staff.holiday.store')->middleware('limitCheck:service,update');

            Route::post('delete/{id}', [Vendor\Staff\StaffHolidayController::class, 'destroy'])->name('vendor.staff.holiday.destroy');

            Route::post('bulk-delete', [Vendor\Staff\StaffHolidayController::class, 'blukDestroy'])->name('vendor.staff.holiday.bulkdestroy');
        });
    });

    // Staff  Service Assign Route
    Route::prefix('staff-services-managment')->group(function () {
        Route::get('/{id}', [Vendor\Staff\StaffServiceController::class, 'index'])->name('vendor.staff_service_assign');
        Route::post('store', [Vendor\Staff\StaffServiceController::class, 'store'])->name('vendor.staff_service_assign.store')->middleware('limitCheck:service,update');
        Route::get('get-service-category/{id}', [Vendor\Staff\StaffServiceController::class, 'getServiceCategory'])->name('vendor.staff.service_category');
        Route::post('delete/{id}', [Vendor\Staff\StaffServiceController::class, 'destroy'])->name('vendor.staff_service_assign.delete');
        Route::post('bulk-delete', [Vendor\Staff\StaffServiceController::class, 'blukDestroy'])->name('vendor.staff_service_assign.blukDestroy');
    });

    // vendor schedule route
    Route::prefix('schedule')->group(function () {
        Route::get('/days', [Vendor\Staff\StaffGlobalDayController::class, 'index'])->name('vendor.staff.global.day');
        Route::post('weekend-change/{id}', [Vendor\Staff\StaffGlobalDayController::class, 'weekendChange'])->name('vendor.weekend.change')->middleware('limitCheck:service,update,downgrade');

        // time slot route
        Route::prefix('days/time-slots')->group(function () {
            Route::get('/', [Vendor\Staff\StaffGlobalHourController::class, 'serviceHour'])->name('vendor.global.time-slot.manage');
            Route::post('/time-store', [Vendor\Staff\StaffGlobalHourController::class, 'store'])->name('vendor.global.time-slot.store')->middleware('limitCheck:service,update');
            Route::post('/time-update', [Vendor\Staff\StaffGlobalHourController::class, 'update'])->name('vendor.global.time-slot.update')->middleware('limitCheck:service,update');
            Route::post('/destroy/{id}', [Vendor\Staff\StaffGlobalHourController::class, 'destroy'])->name('vendor.global.time-slot.destroy');
            Route::post('/bulk-delete', [Vendor\Staff\StaffGlobalHourController::class, 'bulkDestroy'])->name('vendor.global.time-slot.bulk_delete');
        });

        // holiday route
        Route::prefix('/holiday')->group(function () {
            Route::get('/', [Vendor\Staff\GlobalHolidayController::class, 'index'])->name('vendor.global.holiday');
            Route::post('/store', [Vendor\Staff\GlobalHolidayController::class, 'store'])->name('vendor.global.holiday.store')->middleware('limitCheck:service,update');
            Route::post('/delete/{id}', [Vendor\Staff\GlobalHolidayController::class, 'destroy'])->name('vendor.global.holiday.delete');
            Route::post('/bulke-destory', [Vendor\Staff\GlobalHolidayController::class, 'blukDestroy'])->name('vendor.global.holiday.bluk-destroy');
        });
    });

    // transaction
    Route::get('transcation', [Vendor\TransactionController::class, 'index'])->name('vendor.transaction');

    // service booking managment route start
    Route::prefix('appointment/')->group(function () {
        Route::get('/settings', [Vendor\AppointmentController::class, 'setting'])->name('vendor.appointments.setting');
        Route::post('update/settings', [Vendor\AppointmentController::class, 'updatesetting'])->name('vendor.appointments.setting_update');

        Route::get('all-appointments', [Vendor\AppointmentController::class, 'index'])->name('vendor.all_appointment');

        Route::get('pending-appointments', [Vendor\AppointmentController::class, 'pendingAppointment'])->name('vendor.pending_appointment');

        Route::get('accepted-appointments', [Vendor\AppointmentController::class, 'acceptedAppointment'])->name('vendor.accepted_appointment');

        Route::get('rejected-appointments', [Vendor\AppointmentController::class, 'rejectedAppointment'])->name('vendor.rejected_appointment');

        Route::post('update/appointment-status/{id}', [Vendor\AppointmentController::class, 'updateAppointmentStatus'])->name('vendor.appointment.update_status');

        Route::post('staff/assign', [Vendor\AppointmentController::class, 'staffAssign'])->name('vendor.appointment.staff_assign');

        Route::get('details/{id}', [Vendor\AppointmentController::class, 'show'])->name('vendor.appointment.details');

        Route::post('delete/{id}', [Vendor\AppointmentController::class, 'destroy'])->name('vendor.appointment.delete');

        Route::post('bulk-destroy', [Vendor\AppointmentController::class, 'bulkDestroy'])->name('vendor.appointment.bulk-destroy');
    });

    Route::prefix('withdraw')->group(function () {
        Route::get('/', [Vendor\VendorWithdrawController::class, 'index'])->name('vendor.withdraw');
        Route::get('/create', [Vendor\VendorWithdrawController::class, 'create'])->name('vendor.withdraw.create');
        Route::get('/get-method/input/{id}', [Vendor\VendorWithdrawController::class, 'get_inputs']);

        Route::get('/balance-calculation/{method}/{amount}', [Vendor\VendorWithdrawController::class, 'balance_calculation']);

        Route::post('/send-request', [Vendor\VendorWithdrawController::class, 'send_request'])->name('vendor.withdraw.send-request')->middleware('limitCheck:service,update');
        Route::post('/witdraw/bulk-delete', [Vendor\VendorWithdrawController::class, 'bulkDelete'])->name('vendor.witdraw.bulk_delete_withdraw');
        Route::post('/witdraw/delete', [Vendor\VendorWithdrawController::class, 'Delete'])->name('vendor.witdraw.delete_withdraw');
    });
    // recived email update and show
    Route::prefix('recevied')->group(function () {

        Route::get('email', [Vendor\RecivedEmailController::class, 'mailToAdmin'])->name('vendor.email.index');
        Route::post('update/email', [Vendor\RecivedEmailController::class, 'updateMailToAdmin'])->name('vendor.email.update')->middleware('limitCheck:service,update,downgrade');
    });

    // plugins
    Route::prefix('plugins')->middleware('limitCheck:service,update,downgrade')->group(function () {
        Route::get('', [Vendor\PluginController::class, 'index'])->name('vendor.plugins.index')->withoutMiddleware('limitCheck:service,update,downgrade');
        Route::post('/zoom/store', [Vendor\PluginController::class, 'zoomUpdate'])->name('vendor.plugins.zoom.store');
        Route::post('/update-google-calendar', [Vendor\PluginController::class, 'updateCalendar'])->name('vendor.update_google_calendar');
    });

    // zoome meeting create
    Route::post('/frontend/zoom/meetings', [Vendor\Staff\ZoomController::class, 'createMeeting'])->name('zoom.meetings.create');
    Route::post('/staff/zoom/token', [Vendor\Staff\ZoomController::class, 'getZoomAccessToken'])->name('zoom.token.create');

    // service-inquiry route
    Route::get('service-inquiry', [Vendor\RecivedEmailController::class, 'message'])->name('vendor.booking.inquiry');

    Route::post(
        'reviced/message/delete/{id}',
        [Vendor\RecivedEmailController::class, 'messageDestroy']
    )->name('vendor.booking.inquiry.destory');

    Route::post('bulk_delete', [Vendor\RecivedEmailController::class, 'bulkDelete'])->name('vendor.booking.inquiry.bulk_delete');
});

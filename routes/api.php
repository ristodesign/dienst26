<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Vendor\AppointmentController as VendorAppointmentController;
use App\Http\Controllers\Api\Vendor\BuyPlanController;
use App\Http\Controllers\Api\Vendor\PluginController;
use App\Http\Controllers\Api\Vendor\RecivedEmailController;
use App\Http\Controllers\Api\Vendor\ServiceController as VendorServiceController;
use App\Http\Controllers\Api\Vendor\StaffController;
use App\Http\Controllers\Api\Vendor\StaffGlobalDayController;
use App\Http\Controllers\Api\Vendor\StaffScheduleController;
use App\Http\Controllers\Api\Vendor\SupportTicketController;
use App\Http\Controllers\Api\Vendor\VendorController as VendorManagerController;
use App\Http\Controllers\Api\Vendor\WithdrawController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/get-basic', [HomeController::class, 'getBasic'])->name('getBasic');

// services routes
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('frontend.services');
    Route::get('/search', [ServiceController::class, 'searchService'])->name('frontend.services.category.search');
    Route::get('/details/{slug}/{id}', [ServiceController::class, 'details'])->name('frontend.service.details');
    Route::post('/send-inquiry-message', [ServiceController::class, 'inQuiryMessage'])->name('frontend.service.inQuiryMessage');
    Route::get('/get-staff-content/{id}', [ServiceController::class, 'staffcontent'])->name('frontend.service.content');
    Route::get('/check-date-time/{id}', [ServiceController::class, 'staffHoliday'])->name('frontend.staff.holiday');
    Route::get('/show-staff-hour', [ServiceController::class, 'staffHour'])->name('frontend.staff.hour');
    Route::get('/billing-form/submit', [ServiceController::class, 'billing'])->name('frontend.services.billing');

    Route::post('/store-review/{id}', [ServiceController::class, 'storeReview'])->name('frontend.service.rating.store')->middleware('auth:sanctum');
    Route::get('addto/wishlist/{id}', [UserController::class, 'add_to_wishlist'])->name('user.wishlist')->middleware('auth:sanctum');
    Route::get('remove/wishlist/{id}', [UserController::class, 'remove_wishlist'])->name('remove.wishlist')->middleware('auth:sanctum');
});

// vendors management routes
Route::get('/vendors', [VendorController::class, 'index'])->name('frontend.vendors');
Route::post('/vendor/contact', [VendorController::class, 'contact'])->name('frontend.vendor.contact');
Route::get('/user/facebook', [UserController::class, 'facebookLogin'])->name('user.login.facebook');

Route::get('/get-lang/{code}', [LanguageController::class, 'getLang']);

// booking routes
Route::post('verfiy-payment', [BookingController::class, 'verfiyPayment'])->name('frontend.service.payment.verfiy');
Route::post('payment-process', [BookingController::class, 'paymentProcess'])->name('frontend.service.payment');

// guest customer routes
Route::prefix('customer')->group(function () {
    Route::get('/signup', [UserController::class, 'signup'])->name('user.signup');
    Route::post('/signup/submit', [UserController::class, 'signupSubmit'])->name('user.signup_submit');

    Route::get('/login', [UserController::class, 'login'])->name('user.login');
    Route::post('/login/submit', [UserController::class, 'loginSubmit'])->name('user.login_submit');

    Route::post('/forget-password', [UserController::class, 'forgetPassword'])->name('user.forget_password');
    Route::post('/reset-password', [UserController::class, 'resetPasswordSubmit'])->name('user.reset_password');
});

// store fcm token
Route::post('/save-fcm-token', [FcmTokenController::class, 'store']);
// authenticated customer routes
Route::prefix('/customer')->middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [UserController::class, 'redirectToDashboard'])->name('user.dashboard');
    Route::get('/wishlist', [UserController::class, 'wishlist'])->name('user.wishlist');

    Route::get('/edit-profile', [UserController::class, 'editProfile'])->name('user.edit_profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('user.update_profile');
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('user.change_password');
    Route::post('/update-password', [UserController::class, 'updatePassword'])->name('user.update_password');

    Route::get('/appointment', [AppointmentController::class, 'appointment'])->name('user.appointment.index');
    Route::get('/appointment/details/{id}', [AppointmentController::class, 'details'])->name('user.appointment.details');

    Route::get('/order', [OrderController::class, 'order'])->name('user.order.index')->middleware('shop.status');
    Route::get('/order/details/{id}', [OrderController::class, 'details'])->name('user.order.details')->middleware('shop.status');
    Route::post('/order/product-download/{product_id}', [OrderController::class, 'download'])->name('user.product_order.product.download')->middleware('shop.status');

    Route::post('/logout', [UserController::class, 'logoutSubmit'])->name('user.logout');
});

/**
 * =================================Vendor Routes =============================
 */
Route::get('/vendor/signup', [VendorManagerController::class, 'signup'])->name('vendor.signup');
Route::post('/vendor/signup/submit', [VendorManagerController::class, 'create'])->name('vendor.create');

Route::get('/vendor/login', [VendorManagerController::class, 'login'])->name('vendor.login');
Route::post('/vendor/login/submit', [VendorManagerController::class, 'authentication'])->name('vendor.login_submit');

Route::prefix('/vendor')->middleware('auth:sanctum_vendor')->group(function () {
    Route::get('/dashboard', [VendorManagerController::class, 'dashboard'])->name('vendor.dashboard');
    Route::post('/updated-password', [VendorManagerController::class, 'updated_password'])->name('vendor.update_password');
    Route::post('/updated-profile', [VendorManagerController::class, 'update_profile'])->name('vendor.update_profile');
    Route::post('/logout', [VendorManagerController::class, 'logout'])->name('vendor.logout');
    Route::get('/subscription-log', [VendorManagerController::class, 'subscription_log'])->name('vendor.subscription_log');
    Route::get('/transcation', [VendorManagerController::class, 'transcation'])->name('vendor.transcation');

    Route::get('/service-inquiry', [VendorServiceController::class, 'message'])->name('vendor.booking.inquiry');

    // buy subscription
    Route::get('/package-list', [BuyPlanController::class, 'index'])->name('vendor.plan.extend.index');
    Route::get('/package/checkout/{package_id}', [BuyPlanController::class, 'checkout'])->name('vendor.plan.extend.checkout');

    // withdraw management
    Route::prefix('withdraw')->group(function () {
        Route::get('/', [WithdrawController::class, 'index'])->name('vendor.withdraw');
        Route::get('/create', [WithdrawController::class, 'create'])->name('vendor.withdraw.create');
        Route::get('/get-method/input/{id}', [WithdrawController::class, 'get_inputs']);
        Route::get('/calculation/{method}/{amount}', [WithdrawController::class, 'balance_calculation']);
        Route::post('/send-request', [WithdrawController::class, 'send_request'])
            ->name('vendor.withdraw.send-request');
        Route::post('/bulk-delete', [WithdrawController::class, 'bulkDelete'])
            ->name('vendor.withdraw.bulk_delete_withdraw');
        Route::post('/delete', [WithdrawController::class, 'delete'])
            ->name('vendor.withdraw.delete_withdraw');
    });

    Route::prefix('recevied')->group(function () {
        Route::get('email', [RecivedEmailController::class, 'mailToAdmin'])->name('vendor.email.index');
        Route::post('update/email', [RecivedEmailController::class, 'updateMailToAdmin'])->name('vendor.email.update');
    });

    // service management
    Route::get('/services', [VendorServiceController::class, 'index'])->name('vendor.service_managment');
    Route::prefix('/service')->group(function () {
        Route::get('/create', [VendorServiceController::class, 'create'])->name('vendor.service_managment.create');
        Route::post('/store', [VendorServiceController::class, 'store'])->name('vendor.service_managment.store');
        Route::get('/edit/{id}', [VendorServiceController::class, 'edit'])->name('vendor.service_managment.edit');
        Route::post('/update/{id}', [VendorServiceController::class, 'update'])->name('vendor.service_managment.update');
        Route::post('/update-status', [VendorServiceController::class, 'servicestatus'])->name('vendor.service_managment.servicestatus');
        Route::post('/destroy/{id}', [VendorServiceController::class, 'destroy'])->name('vendor.service_managment.destroy');
        Route::post('/bulk-destroy', [VendorServiceController::class, 'bulkDestroy'])->name('vendor.service_managment.bulkDestroy');
    });

    // staff management
    Route::prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('vendor.staff.index');
        Route::get('/create', [StaffController::class, 'create'])->name('vendor.staff.create');
        Route::post('/store', [StaffController::class, 'store'])->name('vendor.staff.store');
        Route::get('/edit/{id}', [StaffController::class, 'edit'])->name('vendor.staff.edit');
        Route::post('/update/{id}', [StaffController::class, 'update'])->name('vendor.staff.update');
        Route::post('/destroy/{id}', [StaffController::class, 'destroy'])->name('vendor.staff.destroy');
        Route::post('/bulk-destroy', [StaffController::class, 'bulkDestroy'])->name('vendor.staff.bulkDestroy');
        Route::post('/staff-status', [StaffController::class, 'staffstatus'])->name('vendor.staff.staffstatus');
        Route::get('/permission/{id}', [StaffController::class, 'permission'])->name('vendor.staff.permission');
        Route::post('/permission-update/{id}', [StaffController::class, 'permissionUpdate'])->name('vendor.staff.permissionUpdate');
        Route::get('/change-password/{id}', [StaffController::class, 'changePassword'])->name('vendor.staff.change_password');
        Route::post('/update-password/{id}', [StaffController::class, 'updatePassword'])->name('vendor.staff.update_password');

        // staff Schedule management
        Route::get('/days/{id}', [StaffScheduleController::class, 'day'])->name('vendor.staff.schedule.index');
        Route::post('/schedule-type/{id}', [StaffScheduleController::class, 'scheduleType'])->name('vendor.staff.schedule.type_chnage');
        Route::get('/time-slots', [StaffScheduleController::class, 'TimeSlots'])->name('vendor.staff.schedule.TimeSlots');
        Route::post('/store/time-slot', [StaffScheduleController::class, 'storeTimeSlot'])->name('vendor.staff.schedule.storeTimeSlot');
        Route::post('/update-time-slot', [StaffScheduleController::class, 'updateSlot'])->name('vendor.staff.schedule.updateSlot');
        Route::post('/change-weekend/{id}', [StaffScheduleController::class, 'weekendChange'])->name('vendor.staff.schedule.weekendChange');
        Route::post('/time-slot/destroy/{id}', [StaffScheduleController::class, 'destroy'])->name('vendor.staff.schedule.timeSlot.destroy');
        Route::post('/time-slot/bulk-destroy', [StaffScheduleController::class, 'bulkDestroy'])->name('vendor.staff.schedule.timeSlot.bulkDestroy');

        // staff holidays management
        Route::get('/holidays/{id}', [StaffScheduleController::class, 'holidays'])->name('vendor.staff.holiday.index');
        Route::post('/holiday/store', [StaffScheduleController::class, 'holidayStore'])->name('vendor.staff.holiday.store');
        Route::post('/holiday/destory/{id}', [StaffScheduleController::class, 'holidayDestory'])->name('vendor.staff.holiday.holidayDestory');
        Route::post('/holiday/bulk-destory', [StaffScheduleController::class, 'holidayBulkDestory'])->name('vendor.staff.holiday.holidayBulkDestory');
    });

    // support ticket management
    Route::prefix('support')->group(function () {
        Route::get('tickets', [SupportTicketController::class, 'index'])->name('vendor.support_tickets');
        Route::post('ticket/store', [SupportTicketController::class, 'store'])->name('vendor.support_ticket.store');
        Route::get('message/{id}', [SupportTicketController::class, 'message'])->name('vendor.support_tickets.message');
        Route::post('zip-upload', [SupportTicketController::class, 'zip_file_upload'])->name('vendor.support_ticket.zip_file.upload');
        Route::post('reply/{id}', [SupportTicketController::class, 'ticketreply'])->name('vendor.support_ticket.reply');
        Route::post('delete/{id}', [SupportTicketController::class, 'delete'])->name('vendor.support_tickets.delete');
    });

    // appointment management
    Route::prefix('appointment')->group(function () {
        Route::get('/settings', [VendorAppointmentController::class, 'setting'])->name('vendor.appointments.setting');
        Route::post('/update/settings', [VendorAppointmentController::class, 'updatesetting'])->name('vendor.appointments.setting_update');

        Route::get('/all', [VendorAppointmentController::class, 'index'])->name('vendor.all_appointment');
        Route::get('/pending', [VendorAppointmentController::class, 'pendingAppointment'])->name('vendor.pending_appointment');
        Route::get('/accepted', [VendorAppointmentController::class, 'acceptedAppointment'])->name('vendor.accepted_appointment');
        Route::get('/rejected', [VendorAppointmentController::class, 'rejectedAppointment'])->name('vendor.rejected_appointment');

        Route::post('/update/appointment-status/{id}', [VendorAppointmentController::class, 'updateAppointmentStatus'])->name('vendor.appointment.update_status');
        Route::post('/staff/assign', [VendorAppointmentController::class, 'staffAssign'])->name('vendor.appointment.staff_assign');

        Route::get('/details/{id}', [VendorAppointmentController::class, 'show'])->name('vendor.appointment.details');
        Route::post('/delete/{id}', [VendorAppointmentController::class, 'destroy'])->name('vendor.appointment.delete');
        Route::post('/bulk-destroy', [VendorAppointmentController::class, 'bulkDestroy'])->name('vendor.appointment.bulk-destroy');
    });

    Route::prefix('plugins')->group(function () {
        Route::get('', [PluginController::class, 'index'])->name('vendor.plugins.index');
        Route::post('/zoom/update', [PluginController::class, 'zoomUpdate'])->name('vendor.plugins.zoom.store');
        Route::post('/calendar/update', [PluginController::class, 'updateCalendar'])->name('vendor.update_google_calendar');
    });

    // staff gloabal days
    Route::prefix('schedule')->group(function () {
        Route::get('/days', [StaffGlobalDayController::class, 'index'])->name('vendor.staff.global.day');
        Route::post('weekend-change/{id}', [StaffGlobalDayController::class, 'weekendChange'])
            ->name('vendor.weekend.change');

        // time slot route
        Route::prefix('time-slots')->group(function () {
            Route::get('/', [StaffGlobalDayController::class, 'serviceHour'])->name('vendor.global.time-slot.manage');
            Route::post('/store', [StaffGlobalDayController::class, 'store'])->name('vendor.global.time-slot.store');
            Route::post('/update', [StaffGlobalDayController::class, 'update'])->name('vendor.global.time-slot.update');
            Route::post('/destroy/{id}', [StaffGlobalDayController::class, 'destroy'])->name('vendor.global.time-slot.destroy');
            Route::post('/bulk-delete', [StaffGlobalDayController::class, 'bulkDestroy'])->name('vendor.global.time-slot.bulk_delete');
        });

        // holiday route
        Route::prefix('/holiday')->group(function () {
            Route::get('/', [StaffGlobalDayController::class, 'holidayIndex'])->name('vendor.global.holiday');
            Route::post('/store', [StaffGlobalDayController::class, 'holidayStore'])->name('vendor.global.holiday.store');
            Route::post('/delete/{id}', [StaffGlobalDayController::class, 'holidayDestroy'])->name('vendor.global.holiday.delete');
            Route::post('/bulke-destory', [StaffGlobalDayController::class, 'holidayBulkDestroy'])->name('vendor.global.holiday.bluk-destroy');
        });
    });
});

// vendor details page route
Route::get('vendor/{username}', [VendorController::class, 'details'])->name('frontend.vendor.details');

<?php

use App\Http\Controllers\Staff;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| staff Interface Routes
|--------------------------------------------------------------------------
*/
// ========== staff login =========
Route::prefix('staff')->group(function () {
    Route::get('login', [Staff\StaffController::class, 'login'])->name('staff.login');
    Route::post('login_submit', [Staff\StaffController::class, 'loginSubmit'])->name('staff.login_submit');
});
Route::prefix('staff')->middleware('auth:staff', 'staffCheck', 'stafflang')->group(function () {
    // language change in admin dashboard
    Route::get('/change-language/{lang}', [Staff\StaffController::class, 'languageChange'])->name('staff.language.change');
    Route::get('dashboard', [Staff\StaffController::class, 'index'])->name('staff.dashboard');
    Route::post('/change-theme', [Staff\StaffController::class, 'changeTheme'])->name('staff.change_theme');
    Route::get('logout', [Staff\StaffController::class, 'logout'])->name('staff.logout');
    Route::get('/change-password', [Staff\StaffController::class, 'change_password'])->name('staff.change_password');
    Route::post('/update-password', [Staff\StaffController::class, 'updated_password'])->name('staff.update_password');
    Route::get('/edit-profile', [Staff\StaffController::class, 'edit_profile'])->name('staff.edit.profile');
    Route::post('/profile/update/{id}', [Staff\StaffController::class, 'update_profile'])->name('staff.update_profile');

    // staff schedule route
    Route::prefix('schedule')->group(function () {
        Route::get('/days', [Staff\StaffDayHourController::class, 'day'])->name('staff.time-slot');
        Route::post('customize/status/change/{id}', [Staff\StaffDayHourController::class, 'changeStaffSetting'])->name('staff.customize.status.change')->middleware('limitCheck:service,update,staff_downgrade');
        Route::post('weekend-change/{id}', [Staff\StaffDayHourController::class, 'weekendChange'])->name('staff.weekend.change')->middleware('limitCheck:service,update,staff_downgrade');

        // time slot route
        Route::prefix('days/time-slots')->group(function () {
            Route::get('/', [Staff\StaffDayHourController::class, 'hour'])->name('staff.hour.manage');
            Route::post('/store', [Staff\StaffDayHourController::class, 'store'])->name('staff.hour.store')->middleware('limitCheck:service,update,staff_downgrade_js');
            Route::post('/update', [Staff\StaffDayHourController::class, 'update'])->name('staff.hour.update')->middleware('limitCheck:service,update,staff_downgrade_js');
            Route::post('/destroy/{id}', [Staff\StaffDayHourController::class, 'destroy'])->name('staff.hour.destroy');
            Route::post('/bulk-delete-hour', [Staff\StaffDayHourController::class, 'bulkDestroy'])->name('staff.hour.bulk_delete');
        });
    });

    // service managment route
    Route::prefix('service-management')->group(function () {
        Route::get('/', [Staff\ServiceController::class, 'index'])->name('staff.service_managment');

        Route::get('create', [Staff\ServiceController::class, 'create'])->name('staff.service_managment.create');
        Route::post('store', [Staff\ServiceController::class, 'store'])->name('staff.service_managment.store')->middleware('limitCheck:service,update,staff_downgrade_js');
        Route::get('get-subcategory/{category_id}', [Staff\ServiceController::class, 'getSucategory'])->name('staff.service_managment.get_subcategory');

        // //service slider image
        Route::post('/img-store', [Staff\ServiceController::class, 'imagesstore'])->name('staff.service.imagesstore');
        Route::post('/img-remove', [Staff\ServiceController::class, 'removeImage'])->name('staff.service.imagermv');
        Route::post('/img-db-remove', [Staff\ServiceController::class, 'imagedbrmv'])->name('staff.service.imgdbrmv');
        Route::get('delete/slider/image', [Staff\ServiceController::class, 'deleteSliderImage'])->name('staff.service.slider.delete');

        Route::get('edit/{id}', [Staff\ServiceController::class, 'edit'])->name('staff.service_managment.edit');

        Route::post('update/{id}', [Staff\ServiceController::class, 'update'])->name('staff.service_managment.update')->middleware('limitCheck:service,update,staff_downgrade_js');

        Route::post('service-status', [Staff\ServiceController::class, 'servicestatus'])->name('staff.service.status.change')->middleware('limitCheck:service,update,staff_downgrade');

        Route::post('delete/{id}', [Staff\ServiceController::class, 'destroy'])->name('staff.service_managment.delete_product');

        Route::post('/bulk-delete-services', [Staff\ServiceController::class, 'bulkDestroy'])->name('staff.service_managment.bulk_delete');
    });

    // appointment managment
    Route::prefix('appointment/')->group(function () {
        Route::get('/', [Staff\AppointmentController::class, 'index'])->name('staff.appointment');
        Route::get('pending-appointments', [Staff\AppointmentController::class, 'pendingAppointment'])->name('staff.pending_appointment');

        Route::get('accepted-appointments', [Staff\AppointmentController::class, 'acceptedAppointment'])->name('staff.accepted_appointment');

        Route::get('rejected-appointments', [Staff\AppointmentController::class, 'rejectedAppointment'])->name('staff.rejected_appointment');

        Route::get('/details/{id}', [Staff\AppointmentController::class, 'show'])->name('staff.appointment.details');
    });

    // plugins
    Route::prefix('plugins')->middleware('limitCheck:service,update,staff_downgrade')->group(function () {
        Route::get('', [Staff\PluginController::class, 'index'])->name('staff.plugins.index')->withoutMiddleware('limitCheck:service,update,staff_downgrade');
        Route::post('/update-google-calendar', [Staff\PluginController::class, 'updateCalendar'])->name('staff.update_google_calendar');
    });

    // message from customer
    Route::get('service-inquiry', [Staff\ServiceInqueryController::class, 'message'])->name('staff.service_inquery.message');

    Route::post(
        'service-inquiry/delete/{id}',
        [Staff\ServiceInqueryController.php::class, 'messageDestroy']
    )->name('staff.service_inquery.message.destory');

    Route::post('bulk_delete', [Staff\ServiceInqueryController::class, 'bulkDelete'])->name('staff.service_inquery.message.bulk_delete');
});

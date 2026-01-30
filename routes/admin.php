<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/

// language change in admin dashboard
Route::get('/change-language/{lang}', [Admin\AdminController::class, 'languageChange'])->name('admin.language.change');
Route::prefix('/admin')->middleware('auth:admin', 'adminlang')->group(function () {

    // admin redirect to dashboard route
    Route::get('/dashboard', [Admin\AdminController::class, 'redirectToDashboard'])->name('admin.dashboard');
    Route::get('/membership-request', [Admin\AdminController::class, 'membershipRequest'])->name('admin.membership-request');
    // change admin-panel theme (dark/light) route
    Route::get('/change-theme', [Admin\AdminController::class, 'changeTheme'])->name('admin.change_theme');

    // admin profile settings route start
    Route::get('/edit-profile', [Admin\AdminController::class, 'editProfile'])->name('admin.edit_profile');

    Route::post('/update-profile', [Admin\AdminController::class, 'updateProfile'])->name('admin.update_profile');

    Route::get('/change-password', [Admin\AdminController::class, 'changePassword'])->name('admin.change_password');

    Route::post('/update-password', [Admin\AdminController::class, 'updatePassword'])->name('admin.update_password');
    // admin profile settings route end

    // admin logout attempt route
    Route::get('/logout', [Admin\AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/monthly-earning', [Admin\AdminController::class, 'monthly_earning'])->name('admin.monthly_earning');

    Route::get('/monthly-profit', [Admin\AdminController::class, 'monthly_profit'])->name('admin.monthly_profit');

    // menu-builder route
    Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
        Route::get('', [Admin\MenuBuilderController::class, 'index'])->name('admin.menu_builder');

        Route::post('/update-menus', [Admin\MenuBuilderController::class, 'update'])->name('admin.menu_builder.update_menus');
    });

    // admin management route
    Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
        // role-permission route
        Route::get('/role-permissions', [Admin\Administrator\RolePermissionController::class, 'index'])->name('admin.admin_management.role_permissions');

        Route::post('/store-role', [Admin\Administrator\RolePermissionController::class, 'store'])->name('admin.admin_management.store_role');

        Route::get(
            '/role/{id}/permissions',
            [Admin\Administrator\RolePermissionController::class, 'permissions']
        )->name('admin.admin_management.role.permissions');

        Route::post('/role/{id}/update-permissions', [Admin\Administrator\RolePermissionController::class, 'updatePermissions'])->name('admin.admin_management.role.update_permissions');

        Route::post('/update-role', [Admin\Administrator\RolePermissionController::class, 'update'])->name('admin.admin_management.update_role');

        Route::post('/delete-role/{id}', [Admin\Administrator\RolePermissionController::class, 'destroy'])->name('admin.admin_management.delete_role');

        // registered admin route
        Route::get('/registered-admins', [Admin\Administrator\SiteAdminController::class, 'index'])->name('admin.admin_management.registered_admins');

        Route::post('/store-admin', [Admin\Administrator\SiteAdminController::class, 'store'])->name('admin.admin_management.store_admin');

        Route::post('/update-status/{id}', [Admin\Administrator\SiteAdminController::class, 'updateStatus'])->name('admin.admin_management.update_status');

        Route::post('/update-admin', [Admin\Administrator\SiteAdminController::class, 'update'])->name('admin.admin_management.update_admin');

        Route::post('/delete-admin/{id}', [Admin\Administrator\SiteAdminController::class, 'destroy'])->name('admin.admin_management.delete_admin');
    });

    // staff management Route
    Route::prefix('staff-managment')->middleware('permission:Staff Managment')->group(function () {
        Route::get('/', [Admin\Staff\StaffController::class, 'index'])->name('admin.staff_managment');
        Route::get('create', [Admin\Staff\StaffController::class, 'create'])->name('admin.staff_managment.create');
        Route::get('check/package', [Admin\Staff\StaffController::class, 'checkPackge'])->name('admin.staff_managment.check_package');
        Route::post('store', [Admin\Staff\StaffController::class, 'store'])->name('admin.staff_managment.store');
        Route::get('edit/{id}', [Admin\Staff\StaffController::class, 'edit'])->name('admin.staff_managment.edit');
        Route::post('update/{id}', [Admin\Staff\StaffController::class, 'update'])->name('admin.staff_managment.update');
        Route::post('delete/{id}', [Admin\Staff\StaffController::class, 'destroy'])->name('admin.staff_managment.delete');
        Route::post('staff/bulkDestroy', [Admin\Staff\StaffController::class, 'bulkDestroy'])->name('admin.staff_managment.bulkDestroy');
        Route::post('staff-status', [Admin\Staff\StaffController::class, 'staffstatus'])->name('admin.status.change');
        Route::get('/secret-login/{id}', [Admin\Staff\StaffController::class, 'secret_login'])->name('admin.staff.secret-login');
        Route::get('/permission/{id}', [Admin\Staff\StaffController::class, 'permission'])->name('admin.staff.permission');
        Route::post('/permission-update/{id}', [Admin\Staff\StaffController::class, 'permissionUpdate'])->name('admin.staff.permission_update');
        Route::get('/change-password/{id}', [Admin\Staff\StaffController::class, 'changePassword'])->name('admin.staff.change_password');
        Route::post('/update-password/{id}', [Admin\Staff\StaffController::class, 'updatePassword'])->name('admin.staff.update_password');

        // Staff Time slots route
        Route::prefix('staff')->group(function () {
            Route::get('/days/{staff_id}', [Admin\Staff\StaffServiceHourController::class, 'day'])->name('admin.service.day');
            Route::get('/time-slots', [Admin\Staff\StaffServiceHourController::class, 'index'])->name('admin.time-slot.manage');
            Route::post('/time-slots/store', [Admin\Staff\StaffServiceHourController::class, 'store'])->name('admin.service-hour.store');
            Route::post('/time-slots/update', [Admin\Staff\StaffServiceHourController::class, 'update'])->name('admin.service-hour.update');
            Route::post('/time-slots/destroy/{id}', [Admin\Staff\StaffServiceHourController::class, 'destroy'])->name('admin.service-houre.destroy');
            Route::post('/time-slots/bulk-delete', [Admin\Staff\StaffServiceHourController::class, 'bulkDestroy'])->name('admin.service-hour.bulk_delete');
            Route::post('change-weekend/{id}', [Admin\Staff\StaffServiceHourController::class, 'weekendChange'])->name('admin.staff.change.weekend');
        });

        // Staff Holiday Route
        Route::prefix('staff-holiday')->group(function () {
            Route::get('index/{id}', [Admin\Staff\StaffHolidayController::class, 'index'])->name('admin.staff.holiday.index');
            Route::post('customize/status/change/{id}', [Admin\Staff\StaffHolidayController::class, 'changeStaffSetting'])->name('admin.customize.status.change');
            Route::post('store', [Admin\Staff\StaffHolidayController::class, 'store'])->name('admin.staff.holiday.store');
            Route::post('delete/{id}', [Admin\Staff\StaffHolidayController::class, 'destroy'])->name('admin.staff.holiday.destroy');
            Route::post('bulk-delete', [Admin\Staff\StaffHolidayController::class, 'blukDestroy'])->name('admin.staff.holiday.bulkdestroy');
        });

        // staff service assign route
        Route::prefix('staff-services-managment')->group(function () {
            Route::get('/{id}', [Admin\Staff\StaffServiceController::class, 'index'])->name('admin.staff_service_assign');
            Route::post('store', [Admin\Staff\StaffServiceController::class, 'store'])->name('admin.staff_service_assign.store');

            Route::post('delete/{id}', [Admin\Staff\StaffServiceController::class, 'destroy'])->name('admin.staff_service_assign.delete');

            Route::post('/bulk-delete-services', [Admin\Staff\StaffServiceController::class, 'bulkDestroy'])->name('admin.staff_service_assign.bulk_delete');
        });
    });

    // admin or vendor schedule route
    Route::prefix('schedule')->middleware('permission:Schedule')->group(function () {
        // settings route
        Route::get('/settings/time-format', [Admin\BasicSettings\BasicController::class, 'timeFormate'])->name('admin.time-formate');
        Route::post('/settings/time-format/update', [Admin\BasicSettings\BasicController::class, 'timeFormateUpdate'])->name('admin.time-formate.update');

        // days route
        Route::prefix('days')->group(function () {
            Route::get('/', [Admin\Staff\StaffGlobalDayController::class, 'index'])->name('admin.staff.global.day');
            Route::post('weekend-change/{id}', [Admin\Staff\StaffGlobalDayController::class, 'weekendChange'])->name('admin.weekend.change');
            Route::get('vendor/days', [Admin\Staff\StaffGlobalDayController::class, 'vendorDays'])->name('admin.vendor.days');

            // time slots route
            Route::prefix('time-slots')->group(function () {
                Route::get('/', [Admin\Staff\StaffGlobalHourController::class, 'serviceHour'])->name('admin.global.time-slot.manage');
                Route::post('/time-store', [Admin\Staff\StaffGlobalHourController::class, 'store'])->name('admin.global.time-slot.store');
                Route::post('/time-update', [Admin\Staff\StaffGlobalHourController::class, 'update'])->name('admin.global.time-slot.update');
                Route::post('/destroy/{id}', [Admin\Staff\StaffGlobalHourController::class, 'destroy'])->name('admin.global.time-slot.destroy');
                Route::post('/bulk-delete', [Admin\Staff\StaffGlobalHourController::class, 'bulkDestroy'])->name('admin.global.time-slot.bulk_delete');
            });
        });

        // holiday route
        Route::prefix('holiday')->group(function () {
            Route::get('/', [Admin\Staff\GlobalHolidayController::class, 'index'])->name('admin.global.holiday');
            Route::post('/store', [Admin\Staff\GlobalHolidayController::class, 'store'])->name('admin.global.holiday.store');
            Route::post('/delete/{id}', [Admin\Staff\GlobalHolidayController::class, 'destroy'])->name('admin.global.holiday.delete');
            Route::post('/bulke-destory', [Admin\Staff\GlobalHolidayController::class, 'blukDestroy'])->name('admin.global.holiday.bluk-destroy');
        });
    });

    // appointment managment route start
    Route::prefix('appointments/')->middleware('permission:Appointments')->group(function () {

        Route::get('/settings', [Admin\Appointment\AppointmentController::class, 'setting'])->name('admin.appointments.setting');
        Route::post('update/settings', [Admin\Appointment\AppointmentController::class, 'updatesetting'])->name('admin.appointments.setting_update');
        Route::get('/all-appointments', [Admin\Appointment\AppointmentController::class, 'index'])->name('admin.all_appointment');

        Route::get('pending-appointments', [Admin\Appointment\AppointmentController::class, 'pendingAppointment'])->name('admin.pending_appointment');

        Route::get('accepted-appointments', [Admin\Appointment\AppointmentController::class, 'acceptedAppointment'])->name('admin.accepted_appointment');

        Route::get('rejected-appointments', [Admin\Appointment\AppointmentController::class, 'rejectedAppointment'])->name('admin.rejected_appointment');

        Route::post('/update/payment-status/{id}', [Admin\Appointment\AppointmentController::class, 'updatePaymentStatus'])->name('admin.appointment.update_payment_status');

        Route::post('/update/refund-status/{id}', [Admin\Appointment\AppointmentController::class, 'updateRefundStatus'])->name('admin.appointment.update_refund_status');

        Route::post('/update/appointment-status/{id}', [Admin\Appointment\AppointmentController::class, 'updateAppointmentStatus'])->name('admin.appointment.update_appointment_status');

        Route::post('/staff/assign', [Admin\Appointment\AppointmentController::class, 'staffAssign'])->name('admin.appointment.staff_assign');

        Route::get('/details/{id}', [Admin\Appointment\AppointmentController::class, 'show'])->name('admin.appointment.details');

        Route::post('/booking-info/delete/{id}', [Admin\Appointment\AppointmentController::class, 'destroy'])->name('admin.appointment.delete');

        Route::post('/bulk-destory', [Admin\Appointment\AppointmentController::class, 'bulkDestroy'])->name('admin.appointment.bulk-destory');
    });
    // withdraw managment start
    Route::prefix('withdraws')->middleware('permission:Withdraws')->group(function () {
        Route::get('payment-method', [Admin\Withdraw\WithdrawController::class, 'index'])->name('admin.withdrawal.index');
        Route::post('payment-method/store', [Admin\Withdraw\WithdrawController::class, 'storePayment'])->name('admin.withdrawal.store.payment');
        Route::post('payment-method/update', [Admin\Withdraw\WithdrawController::class, 'updatePayment'])->name('admin.withdrawal.update.payment');
        Route::post('payment-method/delete/{id}', [Admin\Withdraw\WithdrawController::class, 'deletePayment'])->name('admin.withdrawal.delete.payment');

        // payment input route
        Route::get('payment-method/input', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'index'])->name('admin.withdraw_payment_method.mange_input');
        Route::post('/payment-method/input-store', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'store'])->name('admin.withdraw_payment_method.store_input');
        Route::get('/payment-method/input-edit/{id}', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'edit'])->name('admin.withdraw_payment_method.edit_input');
        Route::post('/payment-method/input-update', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'update'])->name('admin.withdraw_payment_method.update_input');
        Route::post('/payment-method/order-update', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'order_update'])->name('admin.withdraw_payment_method.order_update');
        Route::get('/payment-method/input-option/{id}', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'get_options'])->name('admin.withdraw_payment_method.options');
        Route::post('/payment-method/input-delete', [Admin\Withdraw\WithdrawPaymentMethodInputController::class, 'delete'])->name('admin.withdraw_payment_method.options_delete');

        Route::get('/withdraw-request', [Admin\Withdraw\WithdrawRequestController::class, 'index'])->name('admin.withdraw.withdraw_request');
        Route::post('/withdraw-request/delete', [Admin\Withdraw\WithdrawRequestController::class, 'delete'])->name('admin.witdraw.delete_withdraw');
        Route::get('/withdraw-request/approve/{id}', [Admin\Withdraw\WithdrawRequestController::class, 'approve'])->name('admin.witdraw.approve_withdraw');

        Route::get('/withdraw-request/decline/{id}', [Admin\Withdraw\WithdrawRequestController::class, 'decline'])->name('admin.witdraw.decline_withdraw');
    });

    // transactions
    Route::get('transactions', [Admin\Transaction\TransactionController::class, 'index'])->name('admin.transaction')->middleware('permission:Transactions');

    // service managment start
    Route::prefix('service-management')->middleware('permission:Service Management')->group(function () {
        // categories
        Route::prefix('settings')->group(function () {
            Route::get('/', [Admin\AdminServiceController::class, 'setting'])->name('admin.service_managment.setting');
            Route::post('/update', [Admin\AdminServiceController::class, 'updateSettings'])->name('admin.service_managment.setting_update');
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', [Admin\ServiceCategoryController::class, 'index'])->name('admin.service_managment.category');
            Route::post('store', [Admin\ServiceCategoryController::class, 'store'])->name('admin.service_managment.category.store');
            Route::post('update', [Admin\ServiceCategoryController::class, 'update'])->name('admin.service_managment.category.update');
            Route::post('delete/{id}', [Admin\ServiceCategoryController::class, 'destroy'])->name('admin.service_managment.category.destory');
            Route::post('/bulk-delete-services_categories', [Admin\ServiceCategoryController::class, 'bulkDestroy'])->name('admin.service_managment.category.bulk_delete');
        });

        // subcategories
        Route::prefix('subcategories')->group(function () {
            Route::get('/', [Admin\ServiceSubcategoryController::class, 'index'])->name('admin.service_managment.subcategory');
            Route::get('/language/{langId}/categories', [Admin\ServiceSubcategoryController::class, 'serviceCategory'])->name('admin.service_managment.search_category');
            Route::post('store', [Admin\ServiceSubcategoryController::class, 'store'])->name('admin.service_managment.subcategory.store');
            Route::post('update', [Admin\ServiceSubcategoryController::class, 'update'])->name('admin.service_managment.subcategory.update');
            Route::post('delete/{id}', [Admin\ServiceSubcategoryController::class, 'destroy'])->name('admin.service_managment.subcategory.destory');
            Route::post('/bulk-delete-services_categories', [Admin\ServiceSubcategoryController::class, 'bulkDestroy'])->name('admin.service_managment.subcategory.bulk_delete');
        });

        // services
        Route::get('/', [Admin\AdminServiceController::class, 'index'])->name('admin.service_managment');
        Route::get('select/vendor', [Admin\AdminServiceController::class, 'vendorSelect'])->name('admin.service_managment.vendor_select');
        Route::get('create', [Admin\AdminServiceController::class, 'create'])->name('admin.service_managment.create');
        Route::get('get-subcategory/{category_id}', [Admin\AdminServiceController::class, 'getSucategory'])->name('admin.service_managment.get_subcategory');

        // service slider image
        Route::post('/img-store', [Admin\AdminServiceController::class, 'imagesstore'])->name('admin.service.imagesstore');
        Route::post('/img-remove', [Admin\AdminServiceController::class, 'removeImage'])->name('admin.service.imagermv');
        Route::post('/img-db-remove', [Admin\AdminServiceController::class, 'imagedbrmv'])->name('admin.service.imgdbrmv');
        Route::get('delete/slider/image', [Admin\AdminServiceController::class, 'deleteSliderImage'])->name('admin.service.slider.delete');
        Route::post('store', [Admin\AdminServiceController::class, 'store'])->name('admin.service_managment.store');
        Route::get('edit/{id}', [Admin\AdminServiceController::class, 'edit'])->name('admin.service_managment.edit');
        Route::post('update/{id}', [Admin\AdminServiceController::class, 'update'])->name('admin.service_managment.update');
        Route::post('delete/{id}', [Admin\AdminServiceController::class, 'destroy'])->name('admin.service_managment.delete');
        Route::post(
            '/bulk-delete-services',
            [Admin\AdminServiceController::class, 'bulkDestroy']
        )->name('admin.service_managment.bulk_delete');
        Route::post('service-status', [Admin\AdminServiceController::class, 'servicestatus'])->name('admin.service.status.change');

        // service promotion
        Route::post('payment/process/', [Admin\AdminServiceController::class, 'featured'])->name('admin.featured.payment');
        // service inquery email
        Route::prefix('service-inquiry')->middleware('permission:Service Inquiry')->group(function () {
            Route::get('/', [Admin\ServiceInqController::class, 'message'])->name('admin.booking.inquiry');
            Route::post('/delete/{id}', [Admin\ServiceInqController::class, 'messageDestroy'])->name('admin.booking.inquiry.destory');
            Route::post('bulk_delete', [Admin\ServiceInqController::class, 'bulkDelete'])->name('admin.booking.inquiry.bulk_delete');
        });

        // featured service managment
        Route::prefix('featured-service')->middleware('permission:Featured Services')->group(function () {
            Route::get('charge', [Admin\FeaturedService\FeaturedServiceController::class, 'charge'])->name('admin.charge.index');

            Route::post('charge/store', [Admin\FeaturedService\FeaturedServiceController::class, 'chargeStore'])->name('admin.charge.store');

            Route::post('charge/update', [Admin\FeaturedService\FeaturedServiceController::class, 'chargeUpdate'])->name('admin.charge.update');

            Route::post('charge/delete/{id}', [Admin\FeaturedService\FeaturedServiceController::class, 'destroy'])->name('admin.charge.delete');

            Route::post('/delete/{id}', [Admin\FeaturedService\FeaturedServiceController::class, 'deleteFeaturedService'])->name('admin.featued-service.delete');

            Route::post('/bulk-destory', [Admin\FeaturedService\FeaturedServiceController::class, 'bulkDestroyFeaturedService'])->name('admin.featued-service.bulk-destory');

            Route::post('bulk-delete-charge', [Admin\FeaturedService\FeaturedServiceController::class, 'bulkDestroy'])->name('admin.charge.bulkdestroy');

            Route::get(
                'all',
                [Admin\FeaturedService\FeaturedServiceController::class, 'featuredService']
            )->name('admin.all-featured.service');

            Route::get('pending', [Admin\FeaturedService\FeaturedServiceController::class, 'pendingFeaturedService'])->name('admin.pending-featured.service');

            Route::get('approved', [Admin\FeaturedService\FeaturedServiceController::class, 'apporvedFeaturedService'])->name('admin.approved-featured.service');

            Route::get('rejected', [Admin\FeaturedService\FeaturedServiceController::class, 'rejectFeaturedService'])->name('admin.rejected-featured.service');

            Route::post('/update-payment-status/{id}', [Admin\FeaturedService\FeaturedServiceController::class, 'updatePaymentStatus'])->name('admin.featured_service.order.update_payment_status');

            Route::post('/update-order-status/{id}', [Admin\FeaturedService\FeaturedServiceController::class, 'updateOrderStatus'])->name('admin.featured_service.order.update_order_status');
        });
    });

    // subscription Log
    Route::get('/subscription-log', [Admin\PaymentLogController::class, 'index'])->name('admin.subscription-log.index');
    Route::post('/payment-log/update', [Admin\PaymentLogController::class, 'update'])->name('admin.payment-log.update');

    // package route
    Route::prefix('package')->group(function () {
        // Package Settings routes
        Route::get('/settings', [Admin\PackageController::class, 'settings'])->name('admin.package.settings');
        Route::post('/settings/update', [Admin\PackageController::class, 'updateSettings'])->name('admin.package.settings.update');
        // Package routes
        Route::get('packages', [Admin\PackageController::class, 'index'])->name('admin.package.index');
        Route::post('package/upload', [Admin\PackageController::class, 'upload'])->name('admin.package.upload');
        Route::post('package/store', [Admin\PackageController::class, 'store'])->name('admin.package.store');
        Route::get('package/{id}/edit', [Admin\PackageController::class, 'edit'])->name('admin.package.edit');
        Route::post('package/update', [Admin\PackageController::class, 'update'])->name('admin.package.update');
        Route::post('package/{id}/uploadUpdate', [Admin\PackageController::class, 'uploadUpdate'])->name('admin.package.uploadUpdate');
        Route::post('package/delete', [Admin\PackageController::class, 'delete'])->name('admin.package.delete');
        Route::post('package/bulk-delete', [Admin\PackageController::class, 'bulkDelete'])->name('admin.package.bulk.delete');
    });

    // shop managment route
    Route::prefix('/shop-management')->middleware('permission:Shop Management')->group(function () {
        // tax route
        Route::get('/tax-amount', [Admin\BasicSettings\BasicController::class, 'productTaxAmount'])->name('admin.shop_management.tax_amount');

        Route::post('/update-tax-amount', [Admin\BasicSettings\BasicController::class, 'updateProductTaxAmount'])->name('admin.shop_management.update_tax_amount');

        Route::get('/settings', [Admin\BasicSettings\BasicController::class, 'settings'])->name('admin.shop_management.settings');

        Route::post('/update-settings', [Admin\BasicSettings\BasicController::class, 'updateSettings'])->name('admin.shop_management.update_settings');

        // shipping charge route
        Route::get('/shipping-charges', [Admin\Shop\ShippingChargeController::class, 'index'])->name('admin.shop_management.shipping_charges');

        Route::post('/store-charge', [Admin\Shop\ShippingChargeController::class, 'store'])->name('admin.shop_management.store_charge');

        Route::post('/update-charge', [Admin\Shop\ShippingChargeController::class, 'update'])->name('admin.shop_management.update_charge');

        Route::post('/delete-charge/{id}', [Admin\Shop\ShippingChargeController::class, 'destroy'])->name('admin.shop_management.delete_charge');

        // coupon route
        Route::get('/coupons', [Admin\Shop\CouponController::class, 'index'])->name('admin.shop_management.coupons');

        Route::post('/store-coupon', [Admin\Shop\CouponController::class, 'store'])->name('admin.shop_management.store_coupon');

        Route::post('/update-coupon', [Admin\Shop\CouponController::class, 'update'])->name('admin.shop_management.update_coupon');

        Route::post('/delete-coupon/{id}', [Admin\Shop\CouponController::class, 'destroy'])->name('admin.shop_management.delete_coupon');

        // product category route
        Route::prefix('/product')->group(function () {
            Route::get('/categories', [Admin\Shop\CategoryController::class, 'index'])->name('admin.shop_management.product.categories');

            Route::post('/store-category', [Admin\Shop\CategoryController::class, 'store'])->name('admin.shop_management.product.store_category');

            Route::post('/update-category', [Admin\Shop\CategoryController::class, 'update'])->name('admin.shop_management.product.update_category');

            Route::post(
                '/delete-category/{id}',
                [Admin\Shop\CategoryController::class, 'destroy']
            )->name('admin.shop_management.product.delete_category');

            Route::post(
                '/bulk-delete-category',
                [Admin\Shop\CategoryController::class, 'bulkDestroy']
            )->name('admin.shop_management.product.bulk_delete_category');
        });

        // product route
        Route::get('/products', [Admin\Shop\ProductController::class, 'index'])->name('admin.shop_management.products');

        Route::get('/select-product-type', [Admin\Shop\ProductController::class, 'productType'])->name('admin.shop_management.select_product_type');

        Route::get(
            '/create-product/{type}',
            [Admin\Shop\ProductController::class, 'create']
        )->name('admin.shop_management.create_product');

        Route::post('/upload-slider-image', [Admin\Shop\ProductController::class, 'uploadImage'])->name('admin.shop_management.upload_slider_image');

        Route::post('/remove-slider-image', [Admin\Shop\ProductController::class, 'removeImage'])->name('admin.shop_management.remove_slider_image');

        Route::post('/store-product', [Admin\Shop\ProductController::class, 'store'])->name('admin.shop_management.store_product');

        Route::post('/product/{id}/update-featured-status', [Admin\Shop\ProductController::class, 'updateFeaturedStatus'])->name('admin.shop_management.product.update_featured_status');

        Route::get(
            '/edit-product/{id}/{type}',
            [Admin\Shop\ProductController::class, 'edit']
        )->name('admin.shop_management.edit_product');

        Route::post('/detach-slider-image', [Admin\Shop\ProductController::class, 'detachImage'])->name('admin.shop_management.detach_slider_image');

        Route::post('/update-product/{id}', [Admin\Shop\ProductController::class, 'update'])->name('admin.shop_management.update_product');

        Route::post('/delete-product/{id}', [Admin\Shop\ProductController::class, 'destroy'])->name('admin.shop_management.delete_product');

        Route::post('/bulk-delete-product', [Admin\Shop\ProductController::class, 'bulkDestroy'])->name('admin.shop_management.bulk_delete_product');

        // order route
        Route::get('/orders', [Admin\Shop\OrderController::class, 'orders'])->name('admin.shop_management.orders');

        Route::prefix('/order/{id}')->group(function () {
            Route::post('/update-payment-status', [Admin\Shop\OrderController::class, 'updatePaymentStatus'])->name('admin.shop_management.order.update_payment_status');
            Route::post('/update-order-status', [Admin\Shop\OrderController::class, 'updateOrderStatus'])->name('admin.shop_management.order.update_order_status');
            Route::get('/details', [Admin\Shop\OrderController::class, 'show'])->name('admin.shop_management.order.details');
            Route::post('/delete', [Admin\Shop\OrderController::class, 'destroy'])->name('admin.shop_management.order.delete');
        });

        Route::post('/bulk-delete-order', [Admin\Shop\OrderController::class, 'bulkDestroy'])->name('admin.shop_management.bulk_delete_order');

        // report route
        Route::get('/report', [Admin\Shop\OrderController::class, 'report'])->name('admin.shop_management.report');

        Route::get('/export-report', [Admin\Shop\OrderController::class, 'exportReport'])->name('admin.shop_management.export_report');
    });

    // user management route
    Route::prefix('/user-management')->middleware('permission:User Management')->group(function () {
        // registered user route
        Route::get('/registered-users', [Admin\User\UserController::class, 'index'])->name('admin.user_management.registered_users');

        Route::get('/create', [Admin\User\UserController::class, 'create'])->name('admin.user_management.registered_user.create');
        Route::post('/store', [Admin\User\UserController::class, 'store'])->name('admin.user_management.registered_user.store');

        Route::prefix('/user/{id}')->group(function () {

            Route::get('/edit', [Admin\User\UserController::class, 'edit'])->name('admin.user_management.registered_user.edit');
            Route::post('/update', [Admin\User\UserController::class, 'update'])->name('admin.user_management.registered_user.update');

            Route::post('/update-account-status', [Admin\User\UserController::class, 'updateAccountStatus'])->name('admin.user_management.user.update_account_status');

            Route::post('/update-email-status', [Admin\User\UserController::class, 'updateEmailStatus'])->name('admin.user_management.user.update_email_status');

            Route::get('/change-password', [Admin\User\UserController::class, 'changePassword'])->name('admin.user_management.user.change_password');

            Route::post('/update-password', [Admin\User\UserController::class, 'updatePassword'])->name('admin.user_management.user.update_password');

            Route::post('/delete', [Admin\User\UserController::class, 'destroy'])->name('admin.user_management.user.delete');
            Route::get('/secret-login', [Admin\User\UserController::class, 'secret_login'])->name('admin.user_management.user.secret-login');
        });

        Route::post('/bulk-delete-user', [Admin\User\UserController::class, 'bulkDestroy'])->name('admin.user_management.bulk_delete_user');

        // subscriber route
        Route::get('/subscribers', [Admin\User\SubscriberController::class, 'index'])->name('admin.user_management.subscribers');

        Route::post('/subscriber/{id}/delete', [Admin\User\SubscriberController::class, 'destroy'])->name('admin.user_management.subscriber.delete');

        Route::post(
            '/bulk-delete-subscriber',
            [Admin\User\SubscriberController::class, 'bulkDestroy']
        )->name('admin.user_management.bulk_delete_subscriber');

        Route::get('/mail-for-subscribers', [Admin\User\SubscriberController::class, 'writeEmail'])->name('admin.user_management.mail_for_subscribers');

        Route::post(
            '/subscribers/send-email',
            [Admin\User\SubscriberController::class, 'prepareEmail']
        )->name('admin.user_management.subscribers.send_email');
    });

    // vendor management route
    Route::prefix('/vendor-management')->middleware('permission:User Management')->group(function () {
        Route::get('/settings', [Admin\VendorManagementController::class, 'settings'])->name('admin.vendor_management.settings');
        Route::post('/settings/update', [Admin\VendorManagementController::class, 'update_setting'])->name('admin.vendor_management.setting.update');

        Route::get('/add-vendor', [Admin\VendorManagementController::class, 'add'])->name('admin.vendor_management.add_vendor');
        Route::post('/save-vendor', [Admin\VendorManagementController::class, 'create'])->name('admin.vendor_management.save-vendor');

        Route::get('/registered-vendors', [Admin\VendorManagementController::class, 'index'])->name('admin.vendor_management.registered_vendor');

        Route::prefix('/vendor/{id}')->group(function () {

            Route::post(
                '/update-account-status',
                [Admin\VendorManagementController::class, 'updateAccountStatus']
            )->name('admin.vendor_management.vendor.update_account_status');

            Route::post('/update-featured-status', [Admin\VendorManagementController::class, 'updateFeaturedStatus'])->name('admin.vendor_management.vendor.update_featured_status');

            Route::post(
                '/update-email-status',
                [Admin\VendorManagementController::class, 'updateEmailStatus']
            )->name('admin.vendor_management.vendor.update_email_status');

            Route::get('/details', [Admin\VendorManagementController::class, 'show'])->name('admin.vendor_management.vendor_details');

            Route::get('/edit', [Admin\VendorManagementController::class, 'edit'])->name('admin.edit_management.vendor_edit');

            Route::post('/update', [Admin\VendorManagementController::class, 'update'])->name('admin.vendor_management.vendor.update_vendor');

            Route::post(
                '/update/vendor/balance',
                [Admin\VendorManagementController::class, 'update_vendor_balance']
            )->name('admin.vendor_management.update_vendor_balance');

            Route::get('/change-password', [Admin\VendorManagementController::class, 'changePassword'])->name('admin.vendor_management.vendor.change_password');

            Route::post('/update-password', [Admin\VendorManagementController::class, 'updatePassword'])->name('admin.vendor_management.vendor.update_password');

            Route::post('/delete', [Admin\VendorManagementController::class, 'destroy'])->name('admin.vendor_management.vendor.delete');

            // add or subtract balance
            Route::get('/balance', [Admin\VendorManagementController::class, 'balance'])->name('admin.edit_management.balance');
            Route::post('/update/vendor/balance', [Admin\VendorManagementController::class, 'update_vendor_balance'])->name('admin.vendor_management.vendor.update_vendor_balance');
        });

        Route::post('/vendor/current-package/remove', [Admin\VendorManagementController::class, 'removeCurrPackage'])->name('vendor.currPackage.remove');

        Route::post('/vendor/current-package/change', [Admin\VendorManagementController::class, 'changeCurrPackage'])->name('vendor.currPackage.change');

        Route::post('/vendor/current-package/add', [Admin\VendorManagementController::class, 'addCurrPackage'])->name('vendor.currPackage.add');

        Route::post('/vendor/next-package/remove', [Admin\VendorManagementController::class, 'removeNextPackage'])->name('vendor.nextPackage.remove');

        Route::post('/vendor/next-package/change', [Admin\VendorManagementController::class, 'changeNextPackage'])->name('vendor.nextPackage.change');

        Route::post(
            '/vendor/next-package/add',
            [Admin\VendorManagementController::class, 'addNextPackage']
        )->name('vendor.nextPackage.add');

        Route::post(
            '/bulk-delete-vendor',
            [Admin\VendorManagementController::class, 'bulkDestroy']
        )->name('admin.vendor_management.bulk_delete_vendor');

        Route::get('/secret-login/{id}', [Admin\VendorManagementController::class, 'secret_login'])->name('admin.vendor_management.vendor.secret_login');
    });
    // mobile interface route
    Route::prefix('mobile-interface')->middleware('permission:Mobile Interface')->group(function () {
        Route::get('/', [Admin\MobileInterfaceController::class, 'index'])->name('admin.mobile_interface');
        Route::get('/home-page-content', [Admin\MobileInterfaceController::class, 'content'])->name('admin.mobile_interface_content');
        Route::post('home-page-content/update', [Admin\MobileInterfaceController::class, 'update'])->name('admin.mobile_interface_update');
        Route::get('/general-setting', [Admin\MobileInterfaceController::class, 'setting'])->name('admin.mobile_interface_gsetting');
        Route::post('/general-setting/update', [Admin\MobileInterfaceController::class, 'settingUpdate'])->name('admin.mobile_interface_gsetting.update');

        Route::get('/online-gateways', [Admin\MobileInterfaceController::class, 'paymentGateways'])->name('admin.mobile_interface.payment_gateways');

        Route::get('/plugins', [Admin\MobileInterfaceController::class, 'plugins'])->name('admin.mobile_interface.plugins');
    });
    // website pages all-route
    Route::prefix('pages')->group(function () {
        // home-page route
        Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
            // about page custom section
            Route::prefix('additional-sections')->group(function () {
                Route::get('sections', [Admin\HomePage\AdditionalSectionController::class, 'index'])->name('admin.home.additional_sections');
                Route::get(
                    'add-section',
                    [Admin\HomePage\AdditionalSectionController::class, 'create']
                )->name('admin.home.additional_section.create');
                Route::post('store-section', [Admin\HomePage\AdditionalSectionController::class, 'store'])->name('admin.home.additional_section.store');
                Route::get('edit-section/{id}', [Admin\HomePage\AdditionalSectionController::class, 'edit'])->name('admin.home.additional_section.edit');
                Route::post('update/{id}', [Admin\HomePage\AdditionalSectionController::class, 'update'])->name('admin.home.additional_section.update');
                Route::post('delete/{id}', [Admin\HomePage\AdditionalSectionController::class, 'delete'])->name('admin.home.additional_section.delete');
                Route::post(
                    'bulkdelete',
                    [Admin\HomePage\AdditionalSectionController::class, 'bulkdelete']
                )->name('admin.home.additional_section.bulkdelete');
            });

            Route::prefix('/work-process')->group(function () {
                Route::post('/store', [Admin\HomePage\WorkProcessController::class, 'storeWorkProcess'])->name('admin.basic_settings.store_work_process');

                Route::post('/update', [Admin\HomePage\WorkProcessController::class, 'updateWorkProcess'])->name('admin.basic_settings.update_work_process');

                Route::post('{id}/delete', [Admin\HomePage\WorkProcessController::class, 'destroyWorkProcess'])->name('admin.basic_settings.delete_work_process');

                Route::post('/bulk-delete', [Admin\HomePage\WorkProcessController::class, 'bulkDestroyWorkProcess'])->name('admin.basic_settings.bulk_delete_work_process');
            });

            // section titles
            Route::get('/images-texts', [Admin\HomePage\SectionController::class, 'sectionContent'])->name('admin.home_page.section_content');
            Route::post('/update/images-texts', [Admin\HomePage\SectionController::class, 'updateContent'])->name('admin.home_page.section_content_update');
            // section customization
            Route::get('/section-customization', [Admin\HomePage\SectionController::class, 'index'])->name('admin.home_page.section_customization');

            Route::post(
                '/update-section-status',
                [Admin\HomePage\SectionController::class, 'update']
            )->name('admin.home_page.update_section_status');

            // banners route
            Route::get('/banners', [Admin\HomePage\BannerController::class, 'index'])->name('admin.home_page.banners');

            Route::post('/store-banners', [Admin\HomePage\BannerController::class, 'store'])->name('admin.home_page.store_banner');

            Route::post('/update-banners', [Admin\HomePage\BannerController::class, 'update'])->name('admin.home_page.update_banner');

            Route::post('/delete-banners/{id}', [Admin\HomePage\BannerController::class, 'destroy'])->name('admin.home_page.delete_banner');
            Route::post('/bulk-delete', [Admin\HomePage\BannerController::class, 'bulkDestroy'])->name('admin.home_page.bulk_delete_banner');
        });
        // work process section
        Route::get('/work-process', [Admin\HomePage\WorkProcessController::class, 'sectionInfo'])->name('admin.home_page.work_process_section');

        Route::prefix('/work-process')->group(function () {
            Route::post('/store', [Admin\HomePage\WorkProcessController::class, 'storeWorkProcess'])->name('admin.home_page.store_work_process');

            Route::post('/update', [Admin\HomePage\WorkProcessController::class, 'updateWorkProcess'])->name('admin.home_page.update_work_process');

            Route::post('{id}/delete', [Admin\HomePage\WorkProcessController::class, 'destroyWorkProcess'])->name('admin.home_page.delete_work_process');

            Route::post('/bulk-delete', [Admin\HomePage\WorkProcessController::class, 'bulkDestroyWorkProcess'])->name('admin.home_page.bulk_delete_work_process');
        });
        // faq route
        Route::prefix('/faqs')->middleware('permission:FAQs')->group(function () {
            Route::get('', [Admin\FaqController::class, 'index'])->name('admin.faq_management');

            Route::post('/store-faq', [Admin\FaqController::class, 'store'])->name('admin.faq_management.store_faq');

            Route::post('/update-faq', [Admin\FaqController::class, 'update'])->name('admin.faq_management.update_faq');

            Route::post('/delete-faq/{id}', [Admin\FaqController::class, 'destroy'])->name('admin.faq_management.delete_faq');

            Route::post('/bulk-delete-faq', [Admin\FaqController::class, 'bulkDestroy'])->name('admin.faq_management.bulk_delete_faq');
        });
        // about-us-page route
        Route::prefix('about-us')->middleware('permission:About Us')->group(function () {
            // about us section
            Route::get('/about', [Admin\AboutUs\AboutSectionController::class, 'about_us'])->name('admin.about_us.index');

            Route::post('/update-about-us', [Admin\AboutUs\AboutSectionController::class, 'update_about_us'])->name('admin.about_us.update');
            Route::get('/testimonial-section', [Admin\HomePage\TestimonialController::class, 'index'])->name('admin.about_us.testimonial_section');
            Route::post('/testimonial-section/update', [Admin\HomePage\TestimonialController::class, 'updateSection'])->name('admin.about_us.testimonial_section_update');
            Route::get('/customize-section', [Admin\AboutUs\AboutSectionController::class, 'customizeSection'])->name('admin.about_us.customize');
            Route::post('/customize-section/update', [Admin\AboutUs\AboutSectionController::class, 'customizeUpdate'])->name('admin.about_us.customize_update');

            // features
            Route::post('/store-features', [Admin\AboutUs\FeaturesController::class, 'storeFeatures'])->name('admin.about_us.store_features');

            Route::post('/update-features', [Admin\AboutUs\FeaturesController::class, 'updateFeatures'])->name('admin.about_us.update_features');

            Route::post('{id}/delete', [Admin\AboutUs\FeaturesController::class, 'destroy'])->name('admin.about_us.delete_features');

            Route::post('/bulk-delete', [Admin\AboutUs\FeaturesController::class, 'bulkDestroy'])->name('admin.about_us.bulk_delete_features');

            // about page custom section
            Route::prefix('additional-sections')->group(function () {
                Route::get('sections', [Admin\AdditionalSectionController::class, 'index'])->name('admin.additional_sections');
                Route::get(
                    'add-section',
                    [Admin\AdditionalSectionController::class, 'create']
                )->name('admin.additional_section.create');
                Route::post('store-section', [Admin\AdditionalSectionController::class, 'store'])->name('admin.additional_section.store');
                Route::get('edit-section/{id}', [Admin\AdditionalSectionController::class, 'edit'])->name('admin.additional_section.edit');
                Route::post('update/{id}', [Admin\AdditionalSectionController::class, 'update'])->name('admin.additional_section.update');
                Route::post('delete/{id}', [Admin\AdditionalSectionController::class, 'delete'])->name('admin.additional_section.delete');
                Route::post(
                    'bulkdelete',
                    [Admin\AdditionalSectionController::class, 'bulkdelete']
                )->name('admin.additional_section.bulkdelete');
            });
        });
        // testimonial section
        Route::get('/testimonials', [Admin\HomePage\TestimonialController::class, 'index'])->name('admin.home_page.testimonial_section');
        Route::prefix('/testimonial')->group(function () {
            Route::post('/store', [Admin\HomePage\TestimonialController::class, 'storeTestimonial'])->name('admin.home_page.store_testimonial');

            Route::post('/update', [Admin\HomePage\TestimonialController::class, 'updateTestimonial'])->name('admin.home_page.update_testimonial');

            Route::post('{id}/delete', [Admin\HomePage\TestimonialController::class, 'destroyTestimonial'])->name('admin.home_page.delete_testimonial');

            Route::post('/bulk-delete', [Admin\HomePage\TestimonialController::class, 'bulkDestroyTestimonial'])->name('admin.home_page.bulk_delete_testimonial');
        });
        // blog management route
        Route::prefix('/blog')->middleware('permission:Blog')->group(function () {
            // blog category route
            Route::get('/categories', [Admin\Journal\CategoryController::class, 'index'])->name('admin.blog_management.categories');

            Route::post('/store-category', [Admin\Journal\CategoryController::class, 'store'])->name('admin.blog_management.store_category');

            Route::post('/update-category', [Admin\Journal\CategoryController::class, 'update'])->name('admin.blog_management.update_category');

            Route::post(
                '/delete-category/{id}',
                [Admin\Journal\CategoryController::class, 'destroy']
            )->name('admin.blog_management.delete_category');

            Route::post(
                '/bulk-delete-category',
                [Admin\Journal\CategoryController::class, 'bulkDestroy']
            )->name('admin.blog_management.bulk_delete_category');

            // blog route
            Route::get('/posts', [Admin\Journal\BlogController::class, 'index'])->name('admin.blog_management.blogs');

            Route::get('/create-blog', [Admin\Journal\BlogController::class, 'create'])->name('admin.blog_management.create_blog');

            Route::post('/store-blog', [Admin\Journal\BlogController::class, 'store'])->name('admin.blog_management.store_blog');

            Route::get('/edit-blog/{id}', [Admin\Journal\BlogController::class, 'edit'])->name('admin.blog_management.edit_blog');

            Route::post('/update-blog/{id}', [Admin\Journal\BlogController::class, 'update'])->name('admin.blog_management.update_blog');

            Route::post('/delete-blog/{id}', [Admin\Journal\BlogController::class, 'destroy'])->name('admin.blog_management.delete_blog');

            Route::post('/bulk-delete-blog', [Admin\Journal\BlogController::class, 'bulkDestroy'])->name('admin.blog_management.bulk_delete_blog');
        });
        // footer route
        Route::prefix('/footer')->middleware('permission:Footer')->group(function () {
            // logo & image route
            Route::get('/logo', [Admin\Footer\ImageController::class, 'index'])->name('admin.footer.logo_and_image');

            Route::post('/update-logo', [Admin\Footer\ImageController::class, 'updateLogo'])->name('admin.footer.update_logo');

            // content route
            Route::get('/content', [Admin\Footer\ContentController::class, 'index'])->name('admin.footer.content');

            Route::post('/update-content', [Admin\Footer\ContentController::class, 'update'])->name('admin.footer.update_content');

            // quick link route
            Route::get('/quick-links', [Admin\Footer\QuickLinkController::class, 'index'])->name('admin.footer.quick_links');

            Route::post('/store-quick-link', [Admin\Footer\QuickLinkController::class, 'store'])->name('admin.footer.store_quick_link');

            Route::post('/update-quick-link', [Admin\Footer\QuickLinkController::class, 'update'])->name('admin.footer.update_quick_link');

            Route::post(
                '/delete-quick-link/{id}',
                [Admin\Footer\QuickLinkController::class, 'destroy']
            )->name('admin.footer.delete_quick_link');
        });
        // seo route
        Route::get('/seo-informations', [Admin\BasicSettings\SEOController::class, 'index'])->name('admin.basic_settings.seo')->middleware('permission:SEO Informations');
        // breadcrumb route
        Route::prefix('breadcrumbs')->middleware('permission:Breadcrumbs')->group(function () {
            Route::get('/image', [Admin\BasicSettings\BasicController::class, 'breadcrumb'])->name('admin.basic_settings.breadcrumb');
            Route::get('/headings', [Admin\BasicSettings\PageHeadingController::class, 'pageHeadings'])->name('admin.basic_settings.page_headings');
        });
        // contact page route
        Route::get('/contact-page', [Admin\BasicSettings\BasicController::class, 'contact_page'])->name('admin.basic_settings.contact_page')->middleware('permission:Contact Page');

        // additional-pages route
        Route::prefix('/additional-pages')->middleware('permission:Additional Pages')->group(function () {
            Route::get('all-pages', [Admin\CustomPageController::class, 'index'])->name('admin.custom_pages');

            Route::get('/add-page', [Admin\CustomPageController::class, 'create'])->name('admin.custom_pages.create_page');

            Route::post('/store-page', [Admin\CustomPageController::class, 'store'])->name('admin.custom_pages.store_page');

            Route::get('/edit-page/{id}', [Admin\CustomPageController::class, 'edit'])->name('admin.custom_pages.edit_page');

            Route::post('/update-page/{id}', [Admin\CustomPageController::class, 'update'])->name('admin.custom_pages.update_page');

            Route::post('/delete-page/{id}', [Admin\CustomPageController::class, 'destroy'])->name('admin.custom_pages.delete_page');

            Route::post('/bulk-delete-page', [Admin\CustomPageController::class, 'bulkDestroy'])->name('admin.custom_pages.bulk_delete_page');
        });
    });

    // ====support tickets ============

    Route::prefix('support-ticket')->group(function () {
        Route::get('/setting', [Admin\SupportTicketController::class, 'setting'])->name('admin.support_ticket.setting');
        Route::post('/setting/update', [Admin\SupportTicketController::class, 'update_setting'])->name('admin.support_ticket.update_setting');
        Route::get('/tickets', [Admin\SupportTicketController::class, 'index'])->name('admin.support_tickets');
        Route::get('/message/{id}', [Admin\SupportTicketController::class, 'message'])->name('admin.support_tickets.message');
        Route::post('/zip-upload', [Admin\SupportTicketController::class, 'zip_file_upload'])->name('admin.support_ticket.zip_file.upload');
        Route::post('/reply/{id}', [Admin\SupportTicketController::class, 'ticketreply'])->name('admin.support_ticket.reply');
        Route::post('/closed/{id}', [Admin\SupportTicketController::class, 'ticket_closed'])->name('admin.support_ticket.close');
        Route::post('/assign-stuff/{id}', [Admin\SupportTicketController::class, 'assign_stuff'])->name('assign_stuff.supoort.ticket');

        Route::get('/unassign-stuff/{id}', [Admin\SupportTicketController::class, 'unassign_stuff'])->name('admin.support_tickets.unassign');

        Route::post('/delete/{id}', [Admin\SupportTicketController::class, 'delete'])->name('admin.support_tickets.delete');
        Route::post('/bulk-delete', [Admin\SupportTicketController::class, 'bulk_delete'])->name('admin.support_tickets.bulk_delete');
    });

    // advertise route
    Route::prefix('/advertise')->middleware('permission:Advertise')->group(function () {
        Route::get('/settings', [Admin\AdvertisementController::class, 'advertiseSettings'])->name('admin.advertise.settings');

        Route::post('/update-settings', [Admin\AdvertisementController::class, 'updateAdvertiseSettings'])->name('admin.advertise.update_settings');

        Route::get('/all-advertisement', [Admin\AdvertisementController::class, 'index'])->name('admin.advertise.all_advertisement');

        Route::get('/preview-image', [Admin\AdvertisementController::class, 'previewImage'])->name('admin.advertise.preview_image');

        Route::post('/store-advertisement', [Admin\AdvertisementController::class, 'store'])->name('admin.advertise.store_advertisement');

        Route::post(
            '/update-advertisement',
            [Admin\AdvertisementController::class, 'update']
        )->name('admin.advertise.update_advertisement');

        Route::post('/delete-advertisement/{id}', [Admin\AdvertisementController::class, 'destroy'])->name('admin.advertise.delete_advertisement');

        Route::post('/bulk-delete-advertisement', [Admin\AdvertisementController::class, 'bulkDestroy'])->name('admin.advertise.bulk_delete_advertisement');
    });

    // announcement-popup route
    Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
        Route::get('', [Admin\PopupController::class, 'index'])->name('admin.announcement_popups');

        Route::get('/select-popup-type', [Admin\PopupController::class, 'popupType'])->name('admin.announcement_popups.select_popup_type');

        Route::get('/create-popup/{type}', [Admin\PopupController::class, 'create'])->name('admin.announcement_popups.create_popup');

        Route::post('/store-popup', [Admin\PopupController::class, 'store'])->name('admin.announcement_popups.store_popup');

        Route::post('/popup/{id}/update-status', [Admin\PopupController::class, 'updateStatus'])->name('admin.announcement_popups.update_popup_status');

        Route::get('/edit-popup/{id}', [Admin\PopupController::class, 'edit'])->name('admin.announcement_popups.edit_popup');

        Route::post('/update-popup/{id}', [Admin\PopupController::class, 'update'])->name('admin.announcement_popups.update_popup');

        Route::post('/delete-popup/{id}', [Admin\PopupController::class, 'destroy'])->name('admin.announcement_popups.delete_popup');

        Route::post('/bulk-delete-popup', [Admin\PopupController::class, 'bulkDestroy'])->name('admin.announcement_popups.bulk_delete_popup');
    });

    // website settings
    Route::prefix('/settings')->middleware('permission:Settings')->group(function () {
        // basic settings information route
        Route::get('/information', [Admin\BasicSettings\BasicController::class, 'information'])->name('admin.basic_settings.information');

        Route::post('/update-info', [Admin\BasicSettings\BasicController::class, 'updateInfo'])->name('admin.basic_settings.update_info');

        Route::get('/general-settings', [Admin\BasicSettings\BasicController::class, 'general_settings'])->name('admin.basic_settings.general_settings');

        Route::post('/update-general-settings', [Admin\BasicSettings\BasicController::class, 'update_general_setting'])->name('admin.basic_settings.general_settings.update');

        Route::post('/update-contact-page', [Admin\BasicSettings\BasicController::class, 'update_contact_page'])->name('admin.basic_settings.contact_page.update');

        // basic settings mail route start
        Route::get('/mail-from-admin', [Admin\BasicSettings\BasicController::class, 'mailFromAdmin'])->name('admin.basic_settings.mail_from_admin');

        Route::post(
            '/update-mail-from-admin',
            [Admin\BasicSettings\BasicController::class, 'updateMailFromAdmin']
        )->name('admin.basic_settings.update_mail_from_admin');

        Route::get('/mail-to-admin', [Admin\BasicSettings\BasicController::class, 'mailToAdmin'])->name('admin.basic_settings.mail_to_admin');

        Route::post(
            '/update-mail-to-admin',
            [Admin\BasicSettings\BasicController::class, 'updateMailToAdmin']
        )->name('admin.basic_settings.update_mail_to_admin');

        Route::get('/mail-templates', [Admin\BasicSettings\MailTemplateController::class, 'index'])->name('admin.basic_settings.mail_templates');

        Route::get('/edit-mail-template/{id}', [Admin\BasicSettings\MailTemplateController::class, 'edit'])->name('admin.basic_settings.edit_mail_template');

        Route::post('/update-mail-template/{id}', [Admin\BasicSettings\MailTemplateController::class, 'update'])->name('admin.basic_settings.update_mail_template');
        // basic settings mail route end

        Route::post('/update-breadcrumb', [Admin\BasicSettings\BasicController::class, 'updateBreadcrumb'])->name('admin.basic_settings.update_breadcrumb');

        Route::post(
            '/update-page-headings',
            [Admin\BasicSettings\PageHeadingController::class, 'updatePageHeadings']
        )->name('admin.basic_settings.update_page_headings');

        // basic settings plugins route start
        Route::get('/plugins', [Admin\BasicSettings\BasicController::class, 'plugins'])->name('admin.basic_settings.plugins');

        // whatsapp manager plugin
        Route::get('/whatsapp-manager-template', [Admin\BasicSettings\WhatsappManagerController::class, 'index'])
            ->name('admin.basic_settings.whatsapp_manager_template');
        Route::get('/whatsapp-manager-template-edit/{id}', [Admin\BasicSettings\WhatsappManagerController::class, 'edit'])
            ->name('admin.basic_settings.whatsapp_manager_template_edit');
        Route::post('/whatsapp-manager-template-update/{id}', [Admin\BasicSettings\WhatsappManagerController::class, 'update'])
            ->name('admin.basic_settings.whatsapp_manager_template_update');

        Route::post('/update-firebase', [Admin\BasicSettings\BasicController::class, 'updateFirebase'])->name('admin.basic_settings.updateFirebase');

        Route::post('/update-disqus', [Admin\BasicSettings\BasicController::class, 'updateDisqus'])->name('admin.basic_settings.update_disqus');

        Route::post('/google-map', [Admin\BasicSettings\BasicController::class, 'googleMap'])->name('admin.basic_settings.update_map');

        Route::post('/update-zoom', [Admin\BasicSettings\BasicController::class, 'updateZoom'])->name('admin.basic_settings.update_zoom');
        Route::post('/update-wp_manager', [Admin\BasicSettings\BasicController::class, 'update_wp_manager'])->name('admin.basic_settings.update_wp_manager');

        Route::post('/google-calender', [Admin\BasicSettings\BasicController::class, 'updateCalender'])->name('admin.basic_settings.update_calender');

        Route::post('/update-tawkto', [Admin\BasicSettings\BasicController::class, 'updateTawkTo'])->name('admin.basic_settings.update_tawkto');

        Route::post('/update-recaptcha', [Admin\BasicSettings\BasicController::class, 'updateRecaptcha'])->name('admin.basic_settings.update_recaptcha');

        Route::post('/update-facebook', [Admin\BasicSettings\BasicController::class, 'updateFacebook'])->name('admin.basic_settings.update_facebook');

        Route::post('/update-google', [Admin\BasicSettings\BasicController::class, 'updateGoogle'])->name('admin.basic_settings.update_google');

        Route::post('/update-whatsapp', [Admin\BasicSettings\BasicController::class, 'updateWhatsApp'])->name('admin.basic_settings.update_whatsapp');
        // basic settings plugins route end

        Route::post('/update-seo', [Admin\BasicSettings\SEOController::class, 'update'])->name('admin.basic_settings.update_seo');

        // basic settings maintenance-mode route
        Route::get('/maintenance-mode', [Admin\BasicSettings\BasicController::class, 'maintenance'])->name('admin.basic_settings.maintenance_mode');

        Route::post('/update-maintenance-mode', [Admin\BasicSettings\BasicController::class, 'updateMaintenance'])->name('admin.basic_settings.update_maintenance_mode');

        // basic settings cookie-alert route
        Route::get('/cookie-alert', [Admin\BasicSettings\CookieAlertController::class, 'cookieAlert'])->name('admin.basic_settings.cookie_alert');

        Route::post('/update-cookie-alert', [Admin\BasicSettings\CookieAlertController::class, 'updateCookieAlert'])->name('admin.basic_settings.update_cookie_alert');

        // basic-settings social-media route
        Route::get('/social-medias', [Admin\BasicSettings\SocialMediaController::class, 'index'])->name('admin.basic_settings.social_medias');

        Route::post('/store-social-media', [Admin\BasicSettings\SocialMediaController::class, 'store'])->name('admin.basic_settings.store_social_media');

        Route::post('/update-social-media', [Admin\BasicSettings\SocialMediaController::class, 'update'])->name('admin.basic_settings.update_social_media');

        Route::post('/delete-social-media/{id}', [Admin\BasicSettings\SocialMediaController::class, 'destroy'])->name('admin.basic_settings.delete_social_media');

        // language management route start
        Route::prefix('/languages')->middleware('permission:Languages')->group(function () {
            Route::get('', [Admin\LanguageController::class, 'index'])->name('admin.language_management');

            Route::post('/store', [Admin\LanguageController::class, 'store'])->name('admin.language_management.store');

            Route::post('/{id}/make-default-language', [Admin\LanguageController::class, 'makeDefault'])->name('admin.language_management.make_default_language');

            Route::post('/update', [Admin\LanguageController::class, 'update'])->name('admin.language_management.update');

            Route::get('/{id}/edit-keyword', [Admin\LanguageController::class, 'editKeyword'])->name('admin.language_management.edit_keyword');
            Route::get('/{id}/edit-admin-keyword', [Admin\LanguageController::class, 'editAdminKeyword'])->name('admin.language_management.edit_admin_keyword');

            Route::post('add-keyword', [Admin\LanguageController::class, 'addKeyword'])
                ->name('admin.language_management.add_keyword');
            Route::post('add-admin-keyword', [Admin\LanguageController::class, 'addAdminKeyword'])
                ->name('admin.language_management.add_admin_keyword');

            Route::post('/{id}/update-keyword', [Admin\LanguageController::class, 'updateKeyword'])
                ->name('admin.language_management.update_keyword');
            Route::post('/{id}/update-admin-keyword', [Admin\LanguageController::class, 'updateAdminKeyword'])
                ->name('admin.language_management.update_admin_keyword');

            Route::post('/{id}/delete', [Admin\LanguageController::class, 'destroy'])->name('admin.language_management.delete');

            Route::get('/{id}/check-rtl', [Admin\LanguageController::class, 'checkRTL']);
            Route::get('/{id}/check-rtl2', [Admin\LanguageController::class, 'checkRTL2']);
        });

        // payment-gateway route
        Route::prefix('/payment-gateways')->middleware('permission:Payment Gateways')->group(function () {
            Route::get('/online-gateways', [Admin\PaymentGateway\OnlineGatewayController::class, 'index'])->name('admin.payment_gateways.online_gateways');

            Route::prefix('/update-gateway')->group(function () {
                Route::post('/iyzico', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateiyzicoInfo'])->name('admin.payment_gateways.update_iyzico_info');
                Route::post('/phonepe', [Admin\PaymentGateway\OnlineGatewayController::class, 'phonepeUpdate'])->name('admin.phonepe.update');
                Route::post('/paytabs', [Admin\PaymentGateway\OnlineGatewayController::class, 'paytabsUpdate'])->name('admin.paytabs.update');
                Route::post('/midtrans', [Admin\PaymentGateway\OnlineGatewayController::class, 'midtransUpdate'])->name('admin.midtrans.update');
                Route::post('/toyyibpay', [Admin\PaymentGateway\OnlineGatewayController::class, 'toyyibpayUpdate'])->name('admin.toyyibpay.update');
                Route::post('/myfatoorah', [Admin\PaymentGateway\OnlineGatewayController::class, 'myfatoorahUpdate'])->name('admin.myfatoorah.update');
                Route::post('/perfect_money', [Admin\PaymentGateway\OnlineGatewayController::class, 'perfect_moneyUpdate'])->name('admin.perfect_money.update');
                Route::post('/xendit', [Admin\PaymentGateway\OnlineGatewayController::class, 'xenditUpdate'])->name('admin.xendit.update');
                Route::post('/yoco', [Admin\PaymentGateway\OnlineGatewayController::class, 'yocoUpdate'])->name('admin.yoco.update');

                Route::post('/paypal', [Admin\PaymentGateway\OnlineGatewayController::class, 'updatePayPalInfo'])->name('admin.payment_gateways.update_paypal_info');
                Route::post('/instamojo', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateInstamojoInfo'])->name('admin.payment_gateways.update_instamojo_info');
                Route::post('/paystack', [Admin\PaymentGateway\OnlineGatewayController::class, 'updatePaystackInfo'])->name('admin.payment_gateways.update_paystack_info');
                Route::post('/flutterwave', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateFlutterwaveInfo'])->name('admin.payment_gateways.update_flutterwave_info');
                Route::post('/razorpay', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateRazorpayInfo'])->name('admin.payment_gateways.update_razorpay_info');
                Route::post('/mercadopago', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateMercadoPagoInfo'])->name('admin.payment_gateways.update_mercadopago_info');
                Route::post('/mollie', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateMollieInfo'])->name('admin.payment_gateways.update_mollie_info');
                Route::post('/stripe', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateStripeInfo'])->name('admin.payment_gateways.update_stripe_info');
                Route::post('/paytm', [Admin\PaymentGateway\OnlineGatewayController::class, 'updatePaytmInfo'])->name('admin.payment_gateways.update_paytm_info');
                Route::post('/anet', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateAnetInfo'])->name('admin.payment_gateways.update_anet_info');
                Route::post('/monify', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateMonify'])->name('admin.payment_gateways.update_monify');
                Route::post('/nowpayments', [Admin\PaymentGateway\OnlineGatewayController::class, 'updateNowPayments'])->name('admin.payment_gateways.update_nowpayments');
            });

            Route::get('/offline-gateways', [Admin\PaymentGateway\OfflineGatewayController::class, 'index'])->name('admin.payment_gateways.offline_gateways');
            Route::prefix('/offline-gateway')->group(function () {
                Route::post('/store', [Admin\PaymentGateway\OfflineGatewayController::class, 'store'])->name('admin.payment_gateways.store_offline_gateway');
                Route::post('/update', [Admin\PaymentGateway\OfflineGatewayController::class, 'update'])->name('admin.payment_gateways.update_offline_gateway');
                Route::post('/update-status/{id}', [Admin\PaymentGateway\OfflineGatewayController::class, 'updateStatus'])->name('admin.payment_gateways.update_status');
                Route::post('/delete/{id}', [Admin\PaymentGateway\OfflineGatewayController::class, 'destroy'])->name('admin.payment_gateways.delete_offline_gateway');
            });
        });
    });
});

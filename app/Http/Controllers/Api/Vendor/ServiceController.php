<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Helpers\VendorPermissionHelper;
use App\Http\Requests\Service\ServiceStoreRequest;
use App\Http\Requests\Service\ServiceUpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\FeaturedService\FeaturedServiceCharge;
use App\Models\FeaturedService\ServicePromotion;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Services\InqueryMessage;
use App\Models\Services\ServiceBooking;
use App\Models\Services\ServiceContent;
use App\Models\Services\ServiceImage;
use App\Models\Services\ServiceReview;
use App\Models\Services\Services;
use App\Models\Services\Wishlist;
use App\Models\Staff\StaffService;
use Auth;
use DB;
use Illuminate\Http\Request;
use Purifier;

class ServiceController extends Controller
{
    public function index()
    {
        // $language = Language::where('code', $request->language)->firstOrFail();
        $language = Language::where('is_default', 1)->first();

        $information['langs'] = Language::all();
        $language_id = $language->id;

        $services = Services::where('vendor_id', Auth::guard('sanctum_vendor')->user()->id)
            ->with(['content' => function ($q) use ($language_id) {
                $q->select('id', 'service_id', 'name', 'slug')
                    ->where('language_id', $language_id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $information['services'] = $services->map(function ($service) {
            $service->service_image = asset('assets/img/services/'.$service->service_image);

            return $service;
        });

        $information['promotionList'] = FeaturedServiceCharge::all();

        $information['currencyInfo'] = $this->getCurrencyInfo();

        $information['online_gateways'] = OnlineGateway::where('status', 1)->get();
        $information['offline_gateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

        // Strip payment
        $stripe = OnlineGateway::where('keyword', 'stripe')->first();
        $stripe_info = json_decode($stripe->information, true);
        $information['stripe_key'] = $stripe_info['key'];

        // Authorize.Net payment
        $authorizeNet = OnlineGateway::where('keyword', 'authorize.net')->first();
        $authorizeInfo = json_decode($authorizeNet->information, true);

        if ($authorizeInfo['sandbox_check'] == 1) {
            $information['authorizeUrl'] = 'https://jstest.authorize.net/v1/Accept.js';
        } else {
            $information['authorizeUrl'] = 'https://js.authorize.net/v1/Accept.js';
        }

        $information['authorize_login_id'] = $authorizeInfo['login_id'];
        $information['authorize_public_key'] = $authorizeInfo['public_key'];

        return response()->json([
            'success' => true,
            'data' => $information,
        ]);
    }

    // crate
    public function create(): JsonResponse
    {
        $information['languages'] = Language::all();
        $information['currencyInfo'] = $this->getCurrencyInfo();

        return response()->json([
            'success' => true,
            'data' => $information,
        ]);
    }

    public function store(ServiceStoreRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            // store featured image in storage
            $service_image = UploadFile::store(public_path('assets/img/services/'), $request->file('service_image'));

            $service = Services::create([
                'price' => $request->price,
                'zoom_meeting' => $request->zoom_meeting ?? 0,
                'calendar_status' => $request->calender_status ?? 0,
                'service_image' => $service_image,
                'vendor_id' => Auth::guard('sanctum_vendor')->user()->id,
                'status' => $request->status,
                'prev_price' => $request->prev_price,
                'max_person' => $request->person,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            // store slider image
            foreach ($request->slider_images as $key => $image) {
                $sliderImagePath = UploadFile::store(public_path('assets/img/services/service-gallery/'), $image);
                ServiceImage::create([
                    'image' => $sliderImagePath,
                    'service_id' => $service->id,
                ]);
            }

            $languages = Language::all();
            foreach ($languages as $language) {
                $code = $language->code;
                if (
                    $language->is_default == 1 ||
                    $request->filled($code.'_name') ||
                    $request->filled($code.'_description') ||
                    $request->filled($code.'_category_id')
                ) {
                    $content = new ServiceContent;
                    $content->service_id = $service->id;
                    $content->language_id = $language->id;
                    $content->category_id = $request[$code.'_category_id'];
                    $content->subcategory_id = $request[$code.'_subcategory_id'];
                    $content->name = $request[$code.'_name'];
                    $content->address = $request[$code.'_address'];
                    $content->slug = createSlug($request[$code.'_name']);
                    $content->description = Purifier::clean($request[$code.'_description']);
                    $content->meta_keyword = $request[$code.'_meta_keyword'];
                    $content->meta_description = $request[$code.'_meta_description'];
                    $content->features = $request[$code.'_features'];
                    $content->save();
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => __('Service created successfully'),
        ]);
    }

    // edit
    public function edit($id)
    {
        $language = Language::query()->where('is_default', '=', 1)->first();
        $vendorId = Auth::guard('sanctum_vendor')->user()->id;
        $current_package = VendorPermissionHelper::packagePermission($vendorId);

        if ($current_package == '[]') {
            return redirect()->route('vendor.service_managment', ['language' => $language->code]);
        } else {

            $mapStatus = Basic::pluck('google_map_status')->first();
            if ($mapStatus == 1) {
                $information['service_address'] = ServiceContent::select('address')->where('service_id', $id)->first();
            }
            $service = Services::with('sliderImage')
                ->where('vendor_id', $vendorId)
                ->findOrFail($id);
            // service featured image
            $service->service_image = asset('assets/img/services/'.$service->service_image);
            // service slider images
            $service->sliderImage = $service->sliderImage->map(function ($image) {
                return asset('assets/img/services/service-gallery/'.$image->image);
            });
            $information['service'] = $service;
            $information['languages'] = Language::all();
            $information['currencyInfo'] = $this->getCurrencyInfo();

            return response()->json([
                'success' => true,
                'data' => $information,
            ]);
        }
    }

    // update
    public function update($id, ServiceUpdateRequest $request): JsonResponse
    {
        $service = Services::find($id);
        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => __('Service not found'),
            ], 404);
        }

        // store servic image in storage
        if ($request->hasFile('service_image')) {
            $newImage = $request->file('service_image');
            $oldImage = $service->service_image;
            $serviceImgName = UploadFile::update(public_path('assets/img/services/'), $newImage, $oldImage);
        }

        // store slider image
        if ($request->slider_images) {
            foreach ($request->slider_images as $key => $image) {
                $sliderImagePath = UploadFile::store(public_path('assets/img/services/service-gallery/'), $image);
                ServiceImage::create([
                    'image' => $sliderImagePath,
                    'service_id' => $service->id,
                ]);
            }
        }

        $languages = Language::all();
        $service->update([
            'price' => $request->price,
            'zoom_meeting' => $request->zoom_meeting ?? 0,
            'calendar_status' => $request->calender_status ?? 0,
            'vendor_id' => Auth::guard('vendor')->user()->id,
            'service_image' => $request->hasFile('service_image') ? $serviceImgName : $service->service_image,
            'status' => $request->status,
            'prev_price' => $request->prev_price,
            'max_person' => $request->person,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        foreach ($languages as $language) {
            $code = $language->code;
            $content = ServiceContent::where('service_id', $service->id)->where('language_id', $language->id)
                ->first();
            if (empty($content)) {
                $content = new ServiceContent;
            }

            if (
                $language->is_default == 1 ||
                $request->filled($code.'_name') ||
                $request->filled($code.'_description') ||
                $request->filled($code.'_category_id')
            ) {
                $content->language_id = $language->id;
                $content->service_id = $service->id;
                $content->category_id = $request[$code.'_category_id'];
                $content->subcategory_id = $request[$code.'_subcategory_id'];
                $content->name = $request[$code.'_name'];
                $content->address = $request[$code.'_address'];
                $content->slug = createSlug($request[$code.'_name']);
                $content->description = Purifier::clean($request[$code.'_description']);
                $content->meta_keyword = $request[$code.'_meta_keyword'];
                $content->meta_description = $request[$code.'_meta_description'];
                $content->features = $request[$code.'_features'];
                $content->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Service updated successfully'),
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $hasService = checkService($id);

        if ($hasService > 0) {
            return response()->json([
                'success' => false,
                'message' => __('First reject or delete the appointment for this service!'),
            ], 400);
        } else {
            /**
             * delete featured service
             */
            $featuredServices = ServicePromotion::where('service_id', $id)->get();
            foreach ($featuredServices as $featuredService) {
                // delete the attachment
                @unlink(public_path('assets/file/attachments/service-promotion/').$featuredService->attachment);
                // delete the invoice
                @unlink(public_path('assets/file/invoices/featured/service/').$featuredService->invoice);
                $featuredService->delete();
            }
            /**
             *delete from appointment
             */
            $appointments = ServiceBooking::where('service_id', $id)->get();
            foreach ($appointments as $appointment) {
                @unlink(public_path('assets/file/invoices/service/').$appointment->invoice);
                @unlink(public_path('assets/file/attachments/service/').$appointment->attachment);
                $appointment->delete();
            }

            ServiceReview::where('service_id', $id)->delete();
            InqueryMessage::where('service_id', $id)->delete();
            Wishlist::where('service_id', $id)->delete();

            /**
             * delete staff assign service
             */
            $staffServices = StaffService::where('service_id', $id)->get();

            foreach ($staffServices as $staffService) {
                $staffService->delete();
            }

            /**
             * delete service slider image
             */
            $service = Services::findOrFail($id);
            $servicesContents = $service->content()->get();
            // delete the service_image
            @unlink(public_path('assets/img/services/').$service->service_image);
            $galleries = $service->sliderImage()->get();

            foreach ($galleries as $gallery) {
                @unlink(public_path('assets/img/services/service-gallery/').$gallery->image);
                $gallery->delete();
            }

            /**
             * delete the service_image
             */
            @unlink(public_path('assets/img/services/').$service->service_image);

            foreach ($servicesContents as $content) {
                $content->delete();
            }

            $service->delete();

            return response()->json([
                'success' => true,
                'message' => __('Service deleted successfully'),
            ]);
        }
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $hasService = checkService($id);
            if ($hasService > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('First reject or delete the appointment for this services!'),
                ], 400);
            } else {
                /**
                 * delete from appointment
                 */
                $appointments = ServiceBooking::where('service_id', $id)->get();

                foreach ($appointments as $appointment) {
                    @unlink(public_path('assets/file/invoices/service/').$appointment->invoice);
                    @unlink(public_path('assets/file/attachments/service/').$appointment->attachment);
                    $appointment->delete();
                }

                $service = Services::where('vendor_id', Auth::guard('sanctum_vendor')->user()->id)->findOrFail($id);

                if ($service) {
                    ServiceReview::where('service_id', $id)->delete();
                    InqueryMessage::where('service_id', $id)->delete();
                    Wishlist::where('service_id', $id)->delete();

                    /**
                     * delete featured service
                     */
                    $featuredServices = ServicePromotion::where('service_id', $id)->get();
                    foreach ($featuredServices as $featuredService) {
                        // delete the attachment
                        @unlink(public_path('assets/file/attachments/service-promotion/').$featuredService->attachment);
                        // delete the invoice
                        @unlink(public_path('assets/file/invoices/featured/service/').$featuredService->invoice);
                        $featuredService->delete();
                    }

                    $servicesContent = ServiceContent::where('service_id', $service->id)->get();

                    /**
                     * delete staff assign service
                     */
                    $staffServices = StaffService::where('service_id', $id)->get();

                    foreach ($staffServices as $staffService) {
                        $staffService->delete();
                    }

                    foreach ($servicesContent as $content) {
                        $content->delete();
                    }

                    $galleries = $service->sliderImage()->get();

                    foreach ($galleries as $gallery) {
                        @unlink(public_path('assets/img/services/service-gallery/').$gallery->image);
                        $gallery->delete();
                    }

                    @unlink(public_path('assets/img/services/').$service->service_image);
                    $service->delete();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('Services deleted successfully'),
        ]);
    }

    public function servicestatus(Request $request): JsonResponse
    {
        $current_package = VendorPermissionHelper::packagePermission(Auth::guard('sanctum_vendor')->user()->id);

        if ($current_package == '[]') {
            return response()->json([
                'success' => false,
                'message' => __('Please buy a package to use this panel!'),
            ], 403);
        } else {
            $staff = Services::where('id', $request->service_id)->first();

            $staff->update([
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Status update successfully!'),
            ], 403);
        }
    }

    public function message(): JsonResponse
    {
        $language = Language::where('code', request()->language)->first();
        if (! $language) {
            $language = Language::where('is_default', 1)->first();
        }
        $language_id = $language->id;
        $information['langs'] = Language::all();

        $information['messages'] = InqueryMessage::with(['serviceContent' => function ($q) use ($language_id) {
            $q->where('language_id', $language_id);
        }])
            ->where('vendor_id', Auth::guard('sanctum_vendor')->user()->id)
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $information,
        ]);
    }
}

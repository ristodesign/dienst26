<?php

namespace App\Http\Controllers\FrontEnd\Booking\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Booking\ServicePaymentController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PaytabsController extends Controller
{
    public function index($arrData, $paymentFor, $cancel_url, $amount)
    {
        Session::put('arrData', $arrData);

        $paytabInfo = paytabInfo();
        $description = $paymentFor.' via paytabs';

        try {
            $response = Http::withHeaders([
                'Authorization' => $paytabInfo['server_key'], // Server Key
                'Content-Type' => 'application/json',
            ])->post($paytabInfo['url'], [
                'profile_id' => $paytabInfo['profile_id'], // Profile ID
                'tran_type' => 'sale',
                'tran_class' => 'ecom',
                'cart_id' => uniqid(),
                'cart_description' => $description,
                'cart_currency' => $paytabInfo['currency'], // set currency by region
                'cart_amount' => round($amount, 2),
                'return' => route('frontend.service_booking.paytabs_notify'),
            ]);

            $responseData = $response->json();

            return response()->json(['redirectURL' => $responseData['redirect_url']]);
        } catch (\Exception $e) {
            return redirect($cancel_url);
        }
    }

    public function notify(Request $request)
    {
        $arrData = Session::get('arrData');
        $resp = $request->all();
        if ($resp['respStatus'] == 'A' && $resp['respMessage'] == 'Authorised') {
            $bookingProcess = new ServicePaymentController;

            zoomCreate($arrData);
            calendarEventCreate($arrData);

            // store product order information in database
            $bookingInfo = $bookingProcess->storeData($arrData);
            // send whatsapp sms
            WhatsAppController::sendMessage($bookingInfo->id, 'customer_booking_confirmation', 'new_booking');

            $type = 'service_payment_approved';
            payemntStatusMail($type, $bookingInfo->id);

            Session::put('complete', 'payment_complete');
            Session::put('paymentInfo', $bookingInfo);
            session()->forget('paymentFor');
            session()->forget('arrData');
            session()->forget('serviceData');

            return redirect()->route('frontend.services');
        } else {
            session()->forget('paymentFor');
            session()->forget('arrData');
            session()->forget('serviceData');

            return redirect()->route('frontend.service_booking.cancel');
        }
    }
}

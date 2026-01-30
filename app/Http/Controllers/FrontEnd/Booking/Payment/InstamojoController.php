<?php

namespace App\Http\Controllers\Frontend\Booking\Payment;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Booking\ServicePaymentController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Helpers\Instamojo;
use App\Models\PaymentGateway\OnlineGateway;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Response;

class InstamojoController extends Controller
{
    private $api;

    public function __construct()
    {

        $data = OnlineGateway::whereKeyword('instamojo')->first();
        $instamojoData = json_decode($data->information, true);

        if ($instamojoData['sandbox_status'] == 1) {
            $this->api = new Instamojo($instamojoData['key'], $instamojoData['token'], 'https://test.instamojo.com/api/1.1/');
        } else {
            $this->api = new Instamojo($instamojoData['key'], $instamojoData['token']);
        }
    }

    public function index($arrData, $paymentFor, $cancel_url, $amount): JsonResponse
    {
        $title = 'Service Booking';
        $notifyURL = route('frontend.service_booking.instamojo.notify');

        $customerName = $arrData['customer_name'];
        $customerEmail = $arrData['customer_email'];
        $customerPhone = $arrData['customer_phone'];
        try {
            $response = $this->api->paymentRequestCreate([
                'purpose' => $title,
                'amount' => round($amount, 2),
                'buyer_name' => $customerName,
                'email' => $customerEmail,
                'send_email' => false,
                'phone' => $customerPhone,
                'send_sms' => false,
                'redirect_url' => $notifyURL,
            ]);

            // put some data in session before redirect to instamojo url
            session()->put('paymentFor', $paymentFor);
            session()->put('arrData', $arrData);

            session()->put('paymentId', $response['id']);

            // Return the redirect URL as part of the JSON response
            return Response::json(['redirectURL' => $response['longurl']]);
        } catch (Exception $e) {
            // Handling the exception
            $errorMessage = json_decode($e->getMessage(), true);

            // Accessing the individual error messages
            foreach ($errorMessage as $errorMessages) {
                foreach ($errorMessages as $errorMessage) {
                    return Response::json(['error' => $errorMessage."\n"], 422);
                }
            }
        }
    }

    public function notify(Request $request): RedirectResponse
    {
        $arrData = session()->get('arrData');
        $paymentId = session()->get('paymentId');

        $urlInfo = $request->all();

        if ($urlInfo['payment_request_id'] == $paymentId) {
            // remove this session datas
            session()->forget('paymentFor');
            session()->forget('arrData');
            session()->forget('paymentId');
            session()->forget('serviceData');

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

            return redirect()->route('frontend.services');
        } else {
            session()->forget('paymentFor');
            session()->forget('arrData');
            session()->forget('paymentId');
            session()->forget('serviceData');

            return redirect()->route('frontend.service_booking.cancel');
        }
    }
}

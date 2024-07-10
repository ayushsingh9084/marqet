<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller as Controller;
use App\Models\TransactionModel;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function sendResponse($result, $message, $code)
    {
        $response = [
            'status' => $code,
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => $code,
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }


    protected function sendOtp($mobile, $otp, $data = [], $headers = [])
    {
        $message = "Dear, your oyecollege login OTP " . $otp . " please do not share the OTP";
        $url = "http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms?AUTH_KEY=859bbe33d6264bb552f12ce5e9c9fd5c&message=" . $message . "&senderId=OYECLG&routeId=1&mobileNos=.$mobile.&smsContentType=english";
        // $apiBaseUrl = env('API_URL');
        // $url = $apiBaseUrl . $url;

        // Make the API request
        // dd($url);
        // dd($data);
        $response = Http::withHeaders($headers)
            ->get($url);

        return true;
        // dd($response);
        // return $this->handleApiResponse($response);
    }
    protected function handleApiResponse(Response $response)
    {
        $data = $response->json();
        // dd($response->json());
        // Check if the response was successful
        if ($response->successful()) {
            // Return the JSON-decoded response body
            return true;
        } else {
            return false;
        }
    }

    public function sendFcmNotification($title, $description = '', $deviceToken)
    {
        $serverKey = env('FCM_SERVER_KEY'); // Get your FCM server key from .env

        $url = 'https://fcm.googleapis.com/fcm/send';
        // $url = 'https://fcm.googleapis.com/v1/projects/earn-pie-9ae69/messages:send';

        // $deviceToken = 'YOUR_DEVICE_TOKEN_HERE'; // Replace with the recipient's device token

        $data = [
            'notification' => [
                'title' => $title,
                'body' => $description,
            ],
            'data' => [
                // Optional data payload
                'key' => 'value',
            ],
            'to' => $deviceToken,
        ];
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            // Check response status and handle accordingly
            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'FCM notification sent successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to send FCM notification: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['success' => false, 'message' => 'Error sending FCM notification: ' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Client\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function makeApiCall($method, $url, $secure = false, $data = [], $headers = [])
    {
        $apiBaseUrl = env('API_URL');
        // dd($apiBaseUrl);
        $url = $apiBaseUrl . $url;
        // dd($url);
        if ($secure) {
            $headers['Authorization'] = 'Bearer ' . session('token');
            $headers['Content-Type'] = 'application/json';
        }
        // Make the API request
        // dd($url);

        // dd($data);


        $response = Http::withHeaders($headers)
            ->$method($url, $data);

        // dd($response);
        return $this->handleApiResponse($response);
    }

    protected function handleApiResponse(Response $response)
    {
        $data = $response->json();
        // dd($response);
        // Check if the response was successful
        if ($response->successful()) {
            // Return the JSON-decoded response body
            $responseData = $data['data'];
            // dd((object)$responseData);
            // Check if the response data is an array
            // if (is_array($responseData)) {
            //     // If it's an array, convert each item to an object
            //     $responseData = collect($responseData)->map(function ($item) {
            //         return (object)$item;
            //     });
            // } else {
            //     // If it's a single item, convert it to an object within a collection
            //     $responseData = (object)$responseData;
            // }

            return (object)[
                'data' => json_decode(json_encode($responseData)),
                'message' => $data['message'],
                'status' => $data['status'],
                'success' => $data['success'],
                'output' => 'success'
            ];
        } else {
            // dd($data);
            // Handle error responses (You can customize this based on your API's error format)
            if ($response->status() === 401) {
                Session::flush();
                throw new \Exception('Unauthorized. User logged out.', 401);
            } else
            if ($response->status() === 404) {
                return (object)[
                    'success' => false,
                    'message' => "Something went Wrong",
                    'data' => $data['data'] ?? [],
                    'status' => 404,
                    'output' => 'error'
                ];
            } else if ($data == null) {
                return (object)[
                    'message' => null,
                    'data' => $data,
                    'success' => false,
                    'status' => 404,
                    'output' => 'error'
                ];
            } else if ($data['status'] === 402 || $data['status'] === 422) {
                return (object)[
                    'validation_error' => true,
                    'success' => false,
                    'message' => $data['message'],
                    'data' => $data['data'] ?? [],
                    'status' => $data['status'],
                    'output' => 'error'
                ];
            } else if (!$data['success']) {
                return (object)[
                    'message' => $data['message'],
                    'status' => $data['status'],
                    'success' => $data['success'],
                    'data' => $data['data'] ?? [],
                    'output' => 'error'
                ];
            } else {
                // For other exceptions, throw the error
                $errorMessage = $data['message'] ?? 'Unknown error';
                $statusCode = $data['status'] ?? 500;
                throw new \Exception($errorMessage, $statusCode);
            }
        }
    }


    protected function logoutUser($msg = "")
    {
        Session::flush();

        return redirect('login')->with('success', $msg);
    }
    protected function makeApiCallOld($obj, $method, $optionalParameters = [])
    {
        // Call the specified method and pass optional parameters
        if ($optionalParameters) {
            $response = call_user_func_array([$obj, $method], $optionalParameters);
        } else {
            $response = $obj->$method();
        }

        // Get data from the response
        $data = $response->getData();
        dd($data);
        if ($data && $data->success) {
            return (object)[
                'data' => $data->data,
                'message' => $data->message,
                'output' => 'success'
            ];
        } else {
            if ($data->status === '402') {
                return (object)[
                    'validation_error' => true,
                    'message' => $data->message,
                    'data' => $data->data ?? []
                ];
            } else if (!$data->success) {
                return (object)[
                    'message' => $data->message,
                    'data' => $data->data ?? [],
                    'output' => 'error'
                ];
            } else {
                // For other exceptions, throw the error
                $errorMessage = $data->message ?? 'Unknown error';
                $statusCode = $data->status ?? 500;
                throw new \Exception($errorMessage, $statusCode);
            }
        }
    }

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
}

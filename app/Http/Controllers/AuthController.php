<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login()
    {

        if (session('token')) {
            return redirect(redirectUser(session('user')->role));
        }
        // dd(Auth::user());
        return view('auth.seller-login'); // 'home' is the name of the Blade view file
    }
    public function loginRequest(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->with('error', "Validation Error");
        }
        $credentials = $request->only('email', 'password');

        $response = $this->makeApiCall('POST', LOGIN, false, $credentials);
        // dd($response);
        if ($response->success) {
            // dd($response);
            Session::put((array)$response->data);
            return redirect(redirectUser(session('user')->role));
        } else {
            return back()->with('error', $response->message);
        }
    }



    public function logout(Request $request)
    {
        Session::flush();

        return redirect('login')->with('success', 'Logout Successfully');
    }

    //VerifyOtp
    public function verifyOtp(Request $request)
    {
        try {
            $response = null;
            $requestData = $request->except('_token');

            $response = $this->makeApiCall('POST', VERIFY_OTP_URL, false, $requestData);

            if (isset($response->validation_error) && $response->validation_error === true || !$response->success) {
                // Validation error occurred, display error message
                // dd($response);
                return back()->withErrors($response->data)->with('error', $response->message)->with('next', 'studentVerifyOTPForm')->withInput();
            } else {
                // Successful operation or other exceptions

                Session::put((array)$response->data);
                return back()->with('success', $response->message);
            }
        } catch (\Exception $e) {
            // Handle other exceptions, if necessary
            return back()->with('error', $e->getMessage());
        }
    }

}

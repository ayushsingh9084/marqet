<?php

namespace App\Http\Controllers\Backend\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Backend\ApiController;
use App\Models\SellerAccount;
use App\Models\MasterModels\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SellerAccountApiController extends ApiController
{
    public function read()
    {
        $data = SellerAccount::all();
        return $this->sendResponse($data, 'Seller Account retrieved successfully', Response::HTTP_OK);
    }

    public function readOne($id)
    {
        $data = SellerAccount::find($id);
        return $this->sendResponse($data, 'Seller Account retrieved successfully', Response::HTTP_OK);
    }



    public function verifyOtp(Request $request, $id = null)
    {
        try {
            $mode = $request->input('mode');
            $value = $request->input('value');
            $otp = $request->input('otp') ?? null;
            $id = Session::get('user')->id ?? $id;

            if ($otp) {
                $seller = SellerAccount::where($mode, $value)->where('last_' . $mode . '_otp', $otp)->first();
                // Check whether OTP is correct 
                if (!$seller) {
                    return $this->sendError('Invalid OTP', 'The provided OTP is invalid', Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                if ($seller) {
                    $property_name = $mode . "_verified_at";
                    $seller->$property_name = now();
                    $seller->save();
                }
            } else {
                // Send OTP
                if ($mode == "mobile") {
                    $seller = SellerAccount::updateOrCreate(['mobile' => $value], [
                        'role' => $request->input('role'),
                        'mobile' => $request->input('mobile'),
                        'added_by' => $id ?? null,
                    ]);
                }
                if ($mode == "email" && $id) {
                    $seller = SellerAccount::find($id);
                    if (!$seller) {
                        return $this->sendError('No user found', 'The User does not exist', Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $seller->email = $value;
                    $seller->business_name = $request->input('business_name') ?? $seller->business_name;
                    $seller->save();
                }

                $otp = generateOTP(6);
                $property_name = 'last_' . $mode . '_otp';
                $seller->$property_name = intval($otp);
                $seller->save();

                // Send OTP to mobile function 

                return $this->sendResponse($seller, 'OTP sent successfully', Response::HTTP_OK);
            }

            $token = null;
            if ($mode == 'mobile') {
                $roleData = Role::select('*')->find($seller->role)->abilities;
                $roleAbilities = ($roleData != null) ? explode(',', $roleData) : ['*'];
                $token = $seller->createToken('MyApiToken', $roleAbilities)->plainTextToken;
                $sessionData = ['token' => $token, 'user' => $seller];
                Session::put('seller', $sessionData);
                Log::info('Session data set:', Session::all()); // Log session data
                return $this->sendResponse($sessionData, 'OTP verified successfully', Response::HTTP_OK);
            }

            return $this->sendResponse($seller, 'OTP verified successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendError('Failed to verify OTP', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(Request $request, $id = null)
    {
        $id = ($id == null) ? Auth::user()->id : $id;

        try {
            $modelinstance = SellerAccount::find($id);
            
            if (!$modelinstance) {
                return $this->sendError('Seller Account not found', 'Seller Account with the given ID does not exist', Response::HTTP_NOT_FOUND);
            }

            

            // Use fill method for PATCH request
            $modelinstance->fill($request->only([
                'role', 'first_name', 'last_name', 'business_name', 'email', 'phone', 'address', 'gst', 'last_phone_otp', 'added_by', 'created_at', 'updated_at', 'deleted_at', 'status'
            ]));

            $modelinstance->save();

            return $this->sendResponse($modelinstance, 'Seller Account updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendError('Failed to update ', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function delete($id)
    {
        try {
            $modelinstance = SellerAccount::find($id);

            if (!$modelinstance) {
                return $this->sendError('Seller Account not found', 'Seller Account with the given ID does not exist', Response::HTTP_NOT_FOUND);
            }

            $modelinstance->delete();

            return $this->sendResponse(null, 'Seller Account soft deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendError('Failed to soft delete ', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend\Seller;

use App\Http\Controllers\Backend\ApiController;
use App\Models\MasterModels\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends ApiController
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                unset($user->password);
                $user->tokens()->delete();

                $roleData = Role::find($user->role)->abilities ?? '';
                $roleAbilities = $roleData ? explode(',', $roleData) : ['*'];

                $token = $user->createToken('MyApiToken', $roleAbilities)->plainTextToken;

                return $this->sendResponse(['token' => $token, 'user' => $user, 'abilities' => $roleAbilities], '', Response::HTTP_CREATED);
            } else {
                return $this->sendError('Invalid Email or Password', 'Invalid Email or Password', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $e) {
            return $this->sendError('Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required'],
            'mobile' => ['required', 'unique:users,mobile'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = Auth::user();
            $modelinstance = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'mobile' => $request->input('mobile'),
                'password' => bcrypt($request->input('password')),
                'added_by' => $user->id,
            ]);

            return $this->sendResponse($modelinstance, 'User created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create user', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();
        Auth::logout();

        return response()->json(['message' => 'Logout Successfully'], Response::HTTP_OK);
    }
}

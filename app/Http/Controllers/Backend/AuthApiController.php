<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\ApiController;
use App\Models\MasterModels\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthApiController extends ApiController
{

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {  
            return $this->sendError('Validation Error', $validator->errors(),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $credentials = $request->only('email', 'password');

        return $this->sendResponse($request->input('email'), '', Response::HTTP_CREATED);
        if (Auth::attempt($credentials)) {  
            return $this->sendResponse($credentials, '', Response::HTTP_CREATED);
            unset(Auth::user()->password);
            Auth::user()->tokens()->delete();

            $roleData = Role::select('*')->find(Auth::user()->role)->abilities;
            // print($roleData);
            $roleAbilities = ($roleData!=null)?explode(',',$roleData):['*'];

            $token = Auth::user()->createToken('MyApiToken', $roleAbilities)->plainTextToken;
            return $this->sendResponse(['token' => $token, 'user' => Auth::user(), 'abilities'=> $roleAbilities], '', Response::HTTP_CREATED);
        } else {
            // return $this->sendResponse(["madldmals"], '', Response::HTTP_CREATED);
        
            return $this->sendError('Invalid Email or Password', 'Invalid Email or Password'. json_encode($credentials), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function create(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'first_name' => ['required'], 
            'last_name' => ['required'], 
            'email' => ['required', 'email'], 
            'role' => ['required'], 
            'mobile' => ['required','unique:users'], 
            'password' => ['required'], 
            'confirm_password' => ['required','same:password'], 
        ]);
        
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(),  Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $user = Auth::user()->id;
            $modelinstance = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'mobile' => $request->input('mobile'),
                'password' => bcrypt($request->input('password')),
                'added_by' => $user,
            ]);

            return $this->sendResponse($modelinstance, 'User created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create ', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
    }


    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('login')->with('success', 'Logout Successfully');
    }
}

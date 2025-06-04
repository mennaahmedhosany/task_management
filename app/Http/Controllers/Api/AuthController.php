<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function  registerUser(RegisterRequest $request)
    {

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $accessToken = $user->createToken('authToken')->plainTextToken;

        // Return a response
        return response()->json(['message' => 'User registered successfully', 'user' => $user, "token" => $accessToken], 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['name' => request('name'), 'password' => request('password')])) {
            $user = Auth::user();
            $user_token['token'] =  $user->createToken('token')->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $user_token,
                'message' => "Login Successful",
                'user' => $user


            ], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorised'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            $request->user()->token()->revoke();
            return response()->json([
                'success' => true,
                'message' => 'Logged out',
            ], 200);
        }
    }
}

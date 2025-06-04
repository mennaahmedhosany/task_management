<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
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
        // Create an access token for the user
        $accessToken = $user->createToken('authToken')->plainTextToken;

        // Return a response
        return response()->json(['message' => 'User registered successfully', 'user' => $user, "token" => $accessToken], 201);
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }


    public function logout(Request $request)
    {
        // Delete the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        try {
            $user = User::create($request->all());
            return ApiResponse::success([
                $user
            ], config('messages.register_user_success'), ApiResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return ApiResponse::error(config('messages.login_failed'), null, ApiResponse::HTTP_UN_AUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'access_token' => $token,
        ], config('messages.login_success'));
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return ApiResponse::success([], config('messages.logout_success'));
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }

    public function user(Request $request)
    {
        try {
            return ApiResponse::success($request->user());
        } catch (\Exception $e) {
            return (new ApiResponse())->handleExceptionErrors($e);
        }
    }
}

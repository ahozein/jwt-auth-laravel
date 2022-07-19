<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->createUser($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User Registered Succesfully!',
            'data' => [
                'accessToken' => $token
            ],
        ], 201);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->loginUser($request->validated());

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong User Credential!',
                'data' => null,
            ], 401);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'User Logged In Succesfully!',
                'data' => [
                    'accessToken' => $token
                ],
            ], 200);
        }
    }

    public function userProfile(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Authenticated User Details.',
            'data' => [
                'user' => Auth::guard('api')->user(),
            ],
        ], 200);
    }

}

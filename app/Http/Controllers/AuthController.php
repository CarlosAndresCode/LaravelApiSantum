<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function register(Request $request): JsonResponse
    {
        $user = $this->userRepository->register($request);

        if (isset($user['error'])) {
            return response()->json(['error' => $user['error']], 400);
        }

        return response()->json(['message' => 'User registered successfully', 'data' => $user], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $result = $this->userRepository->login($request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 401);
        }

        return response()->json([
            'message' => 'User logged in successfully',
            'data' => [
                'access_token' => $result['access_toke'],
                'token_type' => $result['token_type'],
            ]
        ], 200);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}

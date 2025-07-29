<?php

namespace App\repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function register(mixed $request): array
    {
        //        $user->sendEmailVerificationNotification(); // Send verification email
        $user = User::where('email', $request['email'])->first();
        if ($user) {
            return [
                'error' => 'User already exists',
            ];
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_toke' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function login(mixed $request): array
    {
        $user = User::where('email', $request['email'])->first();

        if (!$user || !password_verify($request['password'], $user->password)) {
            return [
                'error' => 'Invalid credentials',
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_toke' => $token,
            'token_type' => 'Bearer',
        ];
    }
}

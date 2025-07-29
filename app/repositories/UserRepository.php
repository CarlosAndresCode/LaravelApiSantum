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

        if (!$user) {
            return [
                'error' => 'User registration failed',
            ];
        }

        $user->sendEmailVerificationNotification();

        return [
            'message' => 'User registered successfully, please check your email for verification.',
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

    public function verifyEmail(mixed $request): array
    {
        $user = User::find($request->route('id'));

        if (!$user || !hash_equals($request->route('hash'), sha1($user->getEmailForVerification()))) {
            return [
                'error' => 'Invalid verification link',
            ];
        }

        $user->markEmailAsVerified();

        return [
            'message' => 'Email verified successfully',
            'access_toke' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}

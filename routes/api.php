<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/users/register', [AuthController::class, 'register']);
Route::post('/users/login', [AuthController::class, 'login']);

Route::middleware('signed')
    ->get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')
    ->post('/users/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
});


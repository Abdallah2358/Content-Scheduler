<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\PostApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest:sanctum']], function () {
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });
    Route::resource(
        'posts',
        PostApiController::class
    );
    Route::get(
        '/posts/{post}/platforms',
        [PostApiController::class, 'platforms']
    );
});

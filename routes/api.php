<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource(
        'posts',
        PostController::class
    );
    Route::get(
        '/posts/{post}/platforms',
        [PostController::class, 'platforms']
    );
});

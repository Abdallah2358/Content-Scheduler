<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('posts', PostController::class)
        ->only(['index', 'create', 'edit']);
    Route::get('user/settings', function () {
        return view('user.settings');
    })->name('user.settings');
});

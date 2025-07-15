<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;

Route::get('/', function () {
    return response()->json(['message' => 'Laravel backend API is running.']);
});

Route::get('/user/google/redirect', [UserAuthController::class, 'redirectToGoogle']);
Route::get('/user/google/callback', [UserAuthController::class, 'handleGoogleCallback']);

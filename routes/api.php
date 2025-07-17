<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\DoctorAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserControllerChat;


// ---------------- USER AUTH ----------------
Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('login/google', [UserAuthController::class, 'loginWithGoogle']);
    Route::middleware(['user.guard'])->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'profile']);
        //..
        //..
        //..
    });
});

// ---------------- ADMIN AUTH ----------------
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('admin.guard')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('profile', [AdminController::class, 'profile']);
        Route::post('create', [AdminController::class, 'createAdmin']);
        Route::get('pending', [AdminController::class, 'pendingDoctors']);
        Route::post('doctor/{id}/accept', [AdminController::class, 'acceptDoctor']);
        Route::post('doctor/{id}/reject', [AdminController::class, 'rejectDoctor']);
    });
});


// ---------------- DOCTOR AUTH ----------------
Route::prefix('doctor')->group(function () {
    Route::post('register', [DoctorAuthController::class, 'register']);
    Route::post('login', [DoctorAuthController::class, 'login']);

    Route::middleware(['doctor.guard'])->group(function () {
        Route::post('logout', [DoctorAuthController::class, 'logout']);
        Route::get('profile', [DoctorController::class, 'profile']);
    });
});


// ---------------- EMAIL VERIFICATION ----------------
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

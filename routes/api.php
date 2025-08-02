<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\DoctorAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HealthController;

// ---------------- HEALTH CHECK ----------------
Route::get('health', [HealthController::class, 'check']);
Route::get('health/detailed', [HealthController::class, 'detailed']);

// ---------------- USER AUTH ----------------
Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('login', [UserAuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('login/google', [UserAuthController::class, 'loginWithGoogle'])->middleware('throttle:10,1');

    Route::middleware(['user.guard'])->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserController::class, 'profile']);

        Route::get('specializations', [UserController::class, 'showAllSpecializations']);
        Route::get('specializations/{id}/doctors', [UserController::class, 'doctorsBySpecialization']);
        Route::get('doctors/{id}', [UserController::class, 'doctorDetails']);

        Route::post('doctor/{id}/request-consultation', [UserController::class, 'requestConsultation']);
        Route::get('all-requests-consultation', [UserController::class, 'showAllReqeustsConsultation']);

        Route::get('chats', [UserController::class, 'userChats']);
        Route::get('chats/{chatId}/messages', [ChatController::class, 'getMessages']);
        Route::post('chats/{chatId}/send', [ChatController::class, 'sendMessage']);
    });
});

// ---------------- DOCTOR AUTH ----------------
Route::prefix('doctor')->group(function () {
    Route::post('register', [DoctorAuthController::class, 'register'])->middleware('throttle:3,1');
    Route::post('login', [DoctorAuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware(['doctor.guard'])->group(function () {
        Route::post('logout', [DoctorAuthController::class, 'logout']);
        Route::get('profile', [DoctorController::class, 'profile']);

        Route::get('consultation/pending', [DoctorController::class, 'pendingConsultationRequests']);
        Route::post('consultation/{id}/respond', [DoctorController::class, 'respondToConsultation']);


        Route::get('chats', [DoctorController::class, 'doctorChats']);
        Route::get('chats/{chatId}/messages', [ChatController::class, 'getMessages']);
        Route::post('chats/{chatId}/send', [ChatController::class, 'sendMessage']);
        Route::post('chats/{chatId}/close', [DoctorController::class, 'closeChat']);
    });
});

// ---------------- ADMIN AUTH ----------------
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware('admin.guard')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('profile', [AdminController::class, 'profile']);

        Route::post('create', [AdminController::class, 'createAdmin']);
        Route::get('pending', [AdminController::class, 'pendingDoctors']);
        Route::post('doctor/{id}/accept', [AdminController::class, 'acceptDoctor']);
        Route::post('doctor/{id}/reject', [AdminController::class, 'rejectDoctor']);
    });
});

// ---------------- EMAIL VERIFICATION ----------------
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification link sent!']);
})->middleware(['auth:sanctum', 'throttle:3,1'])->name('verification.send');


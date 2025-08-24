<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Rute untuk halaman utama, mengarahkan ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Rute untuk dashboard, hanya bisa diakses setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/attendance', function () {
    return view('attendance');
})->middleware(['auth'])->name('attendance');

Route::get('/todo', function () {
    return view('todo');
})->middleware(['auth'])->name('todo');

Route::get('/schedule', function () {
    return view('schedule');
})->middleware(['auth'])->name('schedule');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rute untuk login
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

// Rute untuk logout
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Rute untuk reset password
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

require __DIR__.'/auth.php';
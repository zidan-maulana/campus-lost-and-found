<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/landing', function () {
    return view('landing');
})->name('landing');

// Authentication Routes (Sesuai Referensi Acuan & Standar Verifikasi Kampus)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verification.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verification.otp.post');
Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verification.otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/messages', function () {
    return view('messages');
})->name('messages');

Route::get('/report/lost', function () {
    return view('report-lost');
})->name('report.lost');

Route::get('/report/found', function () {
    return view('report-found');
})->name('report.found');

Route::get('/report/{id}', function ($id) {
    return view('report-detail', compact('id'));
})->name('report.detail');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::get('/my-reports', function () {
    return view('my-reports');
})->name('my.reports');

Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');

Route::get('/saved-reports', function () {
    return view('saved-reports');
})->name('saved.reports');

Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');





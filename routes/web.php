<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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





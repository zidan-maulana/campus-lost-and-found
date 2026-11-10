<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Api\AbuseReportApiController;
use App\Http\Controllers\Api\BookmarkApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\ClaimApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MyReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportPageController;
use App\Http\Controllers\SavedReportsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
| Pengunjung tanpa login boleh melihat daftar, detail, dan mencari laporan
| (DEC-020). Tindakan terproteksi memerlukan login + verifikasi email.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Landing page front-end (resources/views/landing.blade.php milik tim front-end).
Route::get('/landing', function () {
    abort_unless(view()->exists('landing'), 404);

    return view('landing');
})->name('landing');

Route::get('/search', [SearchController::class, 'index'])->name('search');

// Rute statis formulir harus didaftarkan sebelum /report/{report} agar tidak
// tertangkap route model binding (mis. "lost" dianggap id laporan).
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/report/lost', [ReportPageController::class, 'createLost'])->name('report.lost');
    Route::get('/report/found', [ReportPageController::class, 'createFound'])->name('report.found');
});

Route::get('/report/{report}', [ReportPageController::class, 'show'])->name('report.detail');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    // Middleware `verified` mengarah ke nama route `verification.notice`,
    // sehingga layar verifikasi OTP front-end memakai nama tersebut.
    Route::get('/verify-otp', [OtpController::class, 'notice'])->name('verification.notice');
    Route::post('/verify-otp', [OtpController::class, 'verify'])
        ->middleware('throttle:10,1')
        ->name('verification.otp.post');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.otp.resend');

    // Jalur lama berbasis tautan bertanda tangan (tetap didukung).
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.email');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Area pengguna terautentikasi + email terverifikasi
|--------------------------------------------------------------------------
| BR-02: fitur inti hanya untuk akun dengan email kampus terverifikasi.
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Aktivitas saya
    Route::get('/my-reports', [MyReportsController::class, 'index'])->name('my.reports');
    Route::get('/saved-reports', [SavedReportsController::class, 'index'])->name('saved.reports');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
});

/*
|--------------------------------------------------------------------------
| API JSON (dipakai UI yang sudah ada — tanpa mengubah markup)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    // Publik
    Route::get('/reports', [ReportApiController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportApiController::class, 'show'])->name('reports.show');

    Route::middleware(['auth', 'verified'])->group(function () {
        // Laporan
        Route::post('/reports', [ReportApiController::class, 'store'])->name('reports.store');
        Route::patch('/reports/{report}', [ReportApiController::class, 'update'])->name('reports.update');
        Route::post('/reports/{report}/cancel', [ReportApiController::class, 'cancel'])->name('reports.cancel');
        Route::post('/reports/{report}/resolve', [ReportApiController::class, 'resolve'])->name('reports.resolve');
        Route::get('/my-reports', [ReportApiController::class, 'mine'])->name('reports.mine');

        // Klaim
        Route::post('/reports/{report}/claims', [ClaimApiController::class, 'store'])->name('claims.store');
        Route::get('/claims', [ClaimApiController::class, 'index'])->name('claims.index');
        Route::post('/claims/{claim}/reject', [ClaimApiController::class, 'reject'])->name('claims.reject');
        Route::post('/claims/{claim}/cancel', [ClaimApiController::class, 'cancel'])->name('claims.cancel');
        Route::post('/claims/{claim}/select', [ClaimApiController::class, 'select'])->name('claims.select');

        // Serah terima
        Route::post('/claims/{claim}/confirm-handover', [ClaimApiController::class, 'confirmHandover'])
            ->name('claims.confirm');

        // Chat
        Route::get('/conversations', [ChatApiController::class, 'conversations'])->name('conversations');
        Route::get('/claims/{claim}/messages', [ChatApiController::class, 'messages'])->name('messages.index');
        Route::post('/claims/{claim}/messages', [ChatApiController::class, 'send'])->name('messages.store');

        // Notifikasi
        Route::get('/notifications', [NotificationApiController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllRead'])->name('notifications.read-all');

        // Bookmark / laporan tersimpan
        Route::get('/bookmarks', [BookmarkApiController::class, 'index'])->name('bookmarks.index');
        Route::post('/bookmarks/{report}', [BookmarkApiController::class, 'toggle'])->name('bookmarks.toggle');

        // Profil & pengaturan
        Route::get('/profile', [ProfileApiController::class, 'show'])->name('profile.show');
        Route::patch('/profile', [ProfileApiController::class, 'update'])->name('profile.update');
        Route::patch('/settings', [ProfileApiController::class, 'updateSettings'])->name('settings.update');

        // Pelaporan penyalahgunaan
        Route::post('/abuse-reports', [AbuseReportApiController::class, 'store'])->name('abuse.store');
    });
});

/*
|--------------------------------------------------------------------------
| Area administrator
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminDashboardController::class, 'storeAdmin'])->name('users.store');
    Route::get('/reports/{report}', [AdminDashboardController::class, 'showReport'])->name('report');
    Route::post('/reports/{report}/hide', [AdminDashboardController::class, 'hideReport'])->name('report.hide');
    Route::post('/reports/{report}/unhide', [AdminDashboardController::class, 'unhideReport'])->name('report.unhide');
    Route::post('/users/{user}/warn', [AdminDashboardController::class, 'warnUser'])->name('user.warn');
    Route::post('/users/{user}/suspend', [AdminDashboardController::class, 'suspendUser'])->name('user.suspend');
    Route::post('/users/{user}/reactivate', [AdminDashboardController::class, 'reactivateUser'])->name('user.reactivate');
    Route::post('/abuse-reports/{abuseReport}/resolve', [AdminDashboardController::class, 'resolveAbuseReport'])
        ->name('abuse.resolve');
});

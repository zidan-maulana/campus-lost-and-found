<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ClaimController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 — kontrak resmi front-end (docs/API_CONTRACT.md)
|--------------------------------------------------------------------------
| Semua response memakai envelope: { success, message, data, meta }.
| Autentikasi memakai session cookie Laravel (Sanctum belum dipasang),
| sehingga klien web wajib mengirim header X-CSRF-TOKEN.
|
| Rute lama /api/* di routes/web.php tetap dipertahankan agar UI Blade
| yang sudah berjalan tidak rusak, dan akan dihapus setelah seluruh view
| front-end memakai /api/v1.
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ---------------------------------------------------------------- publik
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    // ------------------------------------------------------- civitas terverifikasi
    Route::middleware(['auth', 'verified', 'active'])->group(function () {
        // Akun
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Laporan
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::post('/reports/{report}/cancel', [ReportController::class, 'cancel'])->name('reports.cancel');

        // Klaim & serah terima
        Route::post('/reports/{report}/claims', [ClaimController::class, 'store'])->name('claims.store');
        Route::post('/claims/{claim}/accept', [ClaimController::class, 'accept'])->name('claims.accept');
        Route::post('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
        Route::post('/claims/{claim}/handover/cancel', [ClaimController::class, 'handoverCancel'])->name('claims.handover.cancel');
        Route::post('/claims/{claim}/handover/confirm', [ClaimController::class, 'handoverConfirm'])->name('claims.handover.confirm');

        // Chat privat
        Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/claims/{claim}/messages', [ChatController::class, 'messages'])->name('chats.messages');
        Route::post('/claims/{claim}/messages', [ChatController::class, 'send'])->name('chats.send');

        // Notifikasi
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

        // Aktivitas & profil civitas
        Route::get('/user/activities', [UserController::class, 'activities'])->name('user.activities');
        Route::get('/user/profile', [UserController::class, 'show'])->name('user.profile');
        Route::put('/user/profile', [UserController::class, 'update'])->name('user.profile.update');
        Route::put('/user/settings', [UserController::class, 'updateSettings'])->name('user.settings.update');
    });

    // ------------------------------------------------------------- administrator
    // Kontrak §9: dilindungi `auth` + role admin (tanpa syarat email terverifikasi).
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/overview', [AdminController::class, 'overview'])->name('overview');

        Route::patch('/reports/{report}/status', [AdminController::class, 'updateReportStatus'])->name('reports.status');
        Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.status');

        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    });
});

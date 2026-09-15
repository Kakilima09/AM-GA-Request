<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// AM Jasa
use App\Http\Controllers\AmServiceKendaraanController;
use App\Http\Controllers\AmServiceFaNonKendaraanController;
use App\Http\Controllers\AmSewaController;
use App\Http\Controllers\AmRenovasiRelokasiController;

// Fixed Asset
use App\Http\Controllers\FaBaruController;
use App\Http\Controllers\FaPenghapusanController;
use App\Http\Controllers\FaPenjualanController;
use App\Http\Controllers\FaMutasiController;

// BAST
use App\Http\Controllers\BastController;
use App\Http\Controllers\BastItemFaController;
use App\Http\Controllers\BastItemJasaController;

// General Affair
use App\Http\Controllers\GaBarangController;
use App\Http\Controllers\GaJasaLemburController;
use App\Http\Controllers\GaRuangMeetingController;

// Master Data
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;

// Super Admin
use App\Http\Controllers\SuperAdmin\ModuleAccessController;

// Approval
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\NotificationController;

// ============================================================
// AUTHENTICATION ROUTES
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// DASHBOARD & PROFILE (Protected)
// ============================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile menggunakan UserController
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/settings', [UserController::class, 'settings'])->name('profile.settings');

    // Approval Management
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/approval/{type}/{id}', [ApprovalController::class, 'handleDetailRedirect'])->name('approval.show');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    // ============================================================
    // MODULES (dengan akses berdasarkan module access)
    // ============================================================

    // ---------- AM JASA ----------
    Route::resource('am-service-kendaraan', AmServiceKendaraanController::class);
    Route::patch('am-service-kendaraan/{service}/approve', [AmServiceKendaraanController::class, 'approve'])
        ->name('am-service-kendaraan.approve');
    Route::patch('am-service-kendaraan/{service}/reject', [AmServiceKendaraanController::class, 'reject'])
        ->name('am-service-kendaraan.reject');

    Route::resource('am-service-fa-non-kendaraan', AmServiceFaNonKendaraanController::class);
    Route::patch('am-service-fa-non-kendaraan/{service}/approve', [AmServiceFaNonKendaraanController::class, 'approve'])
        ->name('am-service-fa-non-kendaraan.approve');
    Route::patch('am-service-fa-non-kendaraan/{service}/reject', [AmServiceFaNonKendaraanController::class, 'reject'])
        ->name('am-service-fa-non-kendaraan.reject');

    Route::resource('am-sewa', AmSewaController::class);
    Route::patch('am-sewa/{sewa}/approve', [AmSewaController::class, 'approve'])
        ->name('am-sewa.approve');
    Route::patch('am-sewa/{sewa}/reject', [AmSewaController::class, 'reject'])
        ->name('am-sewa.reject');

    Route::resource('am-renovasi', AmRenovasiRelokasiController::class);
    Route::patch('am-renovasi/{renovasi}/approve', [AmRenovasiRelokasiController::class, 'approve'])
        ->name('am-renovasi.approve');
    Route::patch('am-renovasi/{renovasi}/reject', [AmRenovasiRelokasiController::class, 'reject'])
        ->name('am-renovasi.reject');

    // ---------- FIXED ASSET ----------
    Route::resource('fa-baru', FaBaruController::class);
    Route::patch('fa-baru/{faBaru}/approve', [FaBaruController::class, 'approve'])
        ->name('fa-baru.approve');
    Route::patch('fa-baru/{faBaru}/reject', [FaBaruController::class, 'reject'])
        ->name('fa-baru.reject');

    Route::resource('fa-penghapusan', FaPenghapusanController::class);
    Route::patch('fa-penghapusan/{penghapusan}/approve', [FaPenghapusanController::class, 'approve'])
        ->name('fa-penghapusan.approve');
    Route::patch('fa-penghapusan/{penghapusan}/reject', [FaPenghapusanController::class, 'reject'])
        ->name('fa-penghapusan.reject');

    Route::resource('fa-penjualan', FaPenjualanController::class);
    Route::patch('fa-penjualan/{penjualan}/approve', [FaPenjualanController::class, 'approve'])
        ->name('fa-penjualan.approve');
    Route::patch('fa-penjualan/{penjualan}/reject', [FaPenjualanController::class, 'reject'])
        ->name('fa-penjualan.reject');

    Route::resource('fa-mutasi', FaMutasiController::class);
    Route::patch('fa-mutasi/{mutasi}/approve', [FaMutasiController::class, 'approve'])
        ->name('fa-mutasi.approve');
    Route::patch('fa-mutasi/{mutasi}/reject', [FaMutasiController::class, 'reject'])
        ->name('fa-mutasi.reject');

    // ---------- BAST ----------
    Route::resource('bast', BastController::class);
    Route::post('bast/{bast}/signature', [BastController::class, 'saveSignature'])->name('bast.save-signature');
    Route::get('bast/{bast}/pdf', [BastController::class, 'exportPdf'])->name('bast.export-pdf');

    // Item BAST (via AJAX)
    Route::prefix('bast/{bast}')->group(function () {
        Route::post('items-fa', [BastItemFaController::class, 'store'])->name('bast.items-fa.store');
        Route::put('items-fa/{item}', [BastItemFaController::class, 'update'])->name('bast.items-fa.update');
        Route::delete('items-fa/{item}', [BastItemFaController::class, 'destroy'])->name('bast.items-fa.destroy');

        Route::post('items-jasa', [BastItemJasaController::class, 'store'])->name('bast.items-jasa.store');
        Route::put('items-jasa/{item}', [BastItemJasaController::class, 'update'])->name('bast.items-jasa.update');
        Route::delete('items-jasa/{item}', [BastItemJasaController::class, 'destroy'])->name('bast.items-jasa.destroy');
    });

    // ---------- GENERAL AFFAIR ----------
    Route::resource('ga-barang', GaBarangController::class);
    Route::patch('ga-barang/{barang}/approve', [GaBarangController::class, 'approve'])
        ->name('ga-barang.approve');
    Route::patch('ga-barang/{barang}/reject', [GaBarangController::class, 'reject'])
        ->name('ga-barang.reject');

    Route::resource('ga-jasa-lembur', GaJasaLemburController::class);
    Route::patch('ga-jasa-lembur/{lembur}/approve', [GaJasaLemburController::class, 'approve'])
        ->name('ga-jasa-lembur.approve');
    Route::patch('ga-jasa-lembur/{lembur}/reject', [GaJasaLemburController::class, 'reject'])
        ->name('ga-jasa-lembur.reject');

    Route::resource('ga-ruang-meeting', GaRuangMeetingController::class);
    Route::patch('ga-ruang-meeting/{meeting}/approve', [GaRuangMeetingController::class, 'approve'])
        ->name('ga-ruang-meeting.approve');
    Route::patch('ga-ruang-meeting/{meeting}/reject', [GaRuangMeetingController::class, 'reject'])
        ->name('ga-ruang-meeting.reject');

    // ---------- MASTER DATA (hanya untuk admin & super admin) ----------
    // Company & Department (tanpa show)
    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::resource('departments', DepartmentController::class)->except(['show']);

    // User Management (Super Admin only, tapi middleware role di controller)
    Route::resource('users', UserController::class);
});

// ============================================================
// SUPER ADMIN SPECIFIC ROUTES
// ============================================================
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        // Module Access
        Route::get('/module-access', [ModuleAccessController::class, 'index'])->name('module-access.index');
        Route::post('/module-access', [ModuleAccessController::class, 'store'])->name('module-access.store');
        // Gunakan URL berbeda untuk update (POST dengan _method=PUT)
        Route::put('/module-access/update', [ModuleAccessController::class, 'update'])->name('module-access.update');
        Route::delete('/module-access/{module}', [ModuleAccessController::class, 'destroy'])->name('module-access.destroy');

        // Super Admin dashboard (opsional, bisa pakai yang sama)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // Super Admin juga bisa mengakses semua modul yang sudah didefinisikan di atas
        // Tidak perlu diulang karena sudah ada di group middleware auth

    });

// ============================================================
// REDIRECT ROOT
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

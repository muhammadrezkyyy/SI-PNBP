<?php

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\PaymentController;
use App\Livewire\Admin\AuditDashboard;
use App\Livewire\Admin\FormBuilder;
use App\Livewire\Customer\BookingWizard;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Halaman Utama → diarahkan ke Booking Wizard
// ──────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('booking');
});

// Booking Wizard (Public / Guest)
Route::get('/booking', BookingWizard::class)->name('booking');

// ──────────────────────────────────────────────
// Autentikasi (Login / Logout)
// ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ──────────────────────────────────────────────
// Rute Pelanggan (Public Payment Upload Link via UUID)
// ──────────────────────────────────────────────
Route::get('/payment/{reservation}', [PaymentController::class, 'show'])
    ->name('customer.payment.show');
    
Route::post('/payment/{reservation}', [PaymentController::class, 'store'])
    ->name('customer.payment.store');

Route::get('/simponi/{payment}', [PaymentController::class, 'viewSimponi'])
    ->name('customer.payment.simponi');

// ──────────────────────────────────────────────
// (Sisa rute auth, misal kalau ada)
// ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    // Kosong untuk saat ini
});

// ──────────────────────────────────────────────
// Rute Admin (Memerlukan Auth & Role Admin)
// ──────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Manajemen Reservasi
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    
    // Manajemen Gedung & Tipe Fasilitas
    Route::get('/buildings', \App\Livewire\Admin\BuildingManager::class)->name('buildings.index');
    Route::get('/facility-types', \App\Livewire\Admin\FacilityTypeManager::class)->name('facility-types.index');
    
    // Pengaturan Aplikasi
    Route::get('/settings', \App\Livewire\Admin\SettingsManager::class)->name('settings.index');
    
    // Manajemen Menu Navigasi
    Route::get('/menus', \App\Livewire\Admin\MenuManager::class)->name('menus.index');
    
    // Upload Tagihan SIMPONI
    Route::get('/reservations/{reservation}/billing', \App\Livewire\Admin\BillingUpload::class)->name('billing.upload');

    // Audit Pembayaran
    Route::get('/audit/{payment}', AuditDashboard::class)->name('audit.show');
    
    // Form Builder (Konfigurasi Form Booking Dinamis)
    Route::get('/form-builder', FormBuilder::class)->name('form-builder');

    // Laporan & Export PDF
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export');

    // Endpoint untuk melihat gambar bukti pembayaran (disimpan di disk 'private')
    Route::get('/receipt/{payment}', function (\App\Models\Payment $payment) {
        if (! $payment->receipt_path) {
            abort(404);
        }
        $path = storage_path('app/private/' . $payment->receipt_path);
        if (! file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    })->name('receipt.view');

    // Endpoint untuk melihat PDF asli SIMPONI
    Route::get('/simponi/{payment}', function (\App\Models\Payment $payment) {
        if (! $payment->simponi_pdf_path) {
            abort(404, "Payment has no simponi_pdf_path");
        }
        $path = storage_path('app/private/' . $payment->simponi_pdf_path);
        if (! file_exists($path)) {
            abort(404, "File does not exist: " . $path);
        }
        return response()->file($path);
    })->name('simponi.view');
});

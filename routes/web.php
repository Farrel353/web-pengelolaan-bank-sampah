<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboard;
use App\Http\Controllers\Nasabah\DepositController as NasabahDeposit;
use App\Http\Controllers\Nasabah\WithdrawalController as NasabahWithdrawal;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\TimbangSampahController as AdminTimbang;
use App\Http\Controllers\Admin\WasteItemController as AdminWasteItem;
use App\Http\Controllers\Admin\NasabahController as AdminNasabah;
use App\Http\Controllers\Admin\WithdrawalApprovalController as AdminWithdrawal;
use App\Http\Controllers\Admin\ReportController as AdminReport;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Bank Sampah (Sesuai Rancangan Umum Konstruksi)
|--------------------------------------------------------------------------
| Aktor:
| 1. Nasabah (Melihat Saldo, Riwayat Setoran, Riwayat Penarikan, Melihat Harga Sampah)
| 2. Pengurus/Admin (Kelola Data Nasabah, Kelola Jenis & Harga Sampah, Catat Setoran Sampah,
|                    Memproses Penarikan Saldo, Melihat Laporan Transaksi)
|--------------------------------------------------------------------------
*/

// Halaman Publik
Route::get('/', [PublicPortalController::class, 'index'])->name('home');
Route::get('/katalog', [PublicPortalController::class, 'katalog'])->name('katalog');
Route::get('/kalkulator', [PublicPortalController::class, 'kalkulator'])->name('kalkulator');
Route::get('/edukasi', [PublicPortalController::class, 'edukasi'])->name('edukasi');

// Autentikasi (Login & Registrasi)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => redirect()->route('login'));
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

// Quick Role Switcher untuk kemudahan pengujian demo
Route::get('/quick-switch/{role}', [AuthController::class, 'quickLogin'])->name('quick.switch');

// ==========================================
// 1. PORTAL NASABAH (4 FITUR UTAMA)
// ==========================================
Route::prefix('nasabah')->middleware(['auth', 'role:nasabah'])->name('nasabah.')->group(function () {
    // Fitur 1: Melihat Saldo & Ringkasan Dashboard
    Route::get('/dashboard', [NasabahDashboard::class, 'index'])->name('dashboard');
    Route::get('/kartu-digital', [NasabahDashboard::class, 'kartu'])->name('kartu');

    // Fitur 2: Melihat Riwayat Setoran
    Route::get('/setor', [NasabahDeposit::class, 'index'])->name('setor.index');
    Route::get('/setor/baru', [NasabahDeposit::class, 'create'])->name('setor.create');
    Route::post('/setor', [NasabahDeposit::class, 'store'])->name('setor.store');
    Route::get('/setor/{id}', [NasabahDeposit::class, 'show'])->name('setor.show');

    // Fitur 3: Melihat Riwayat Penarikan & Form Penarikan Saldo
    Route::get('/tarik', [NasabahWithdrawal::class, 'index'])->name('tarik.index');
    Route::get('/tarik/baru', [NasabahWithdrawal::class, 'create'])->name('tarik.create');
    Route::post('/tarik', [NasabahWithdrawal::class, 'store'])->name('tarik.store');

    // Fitur 4: Melihat Harga Sampah
    Route::get('/harga-sampah', [NasabahDashboard::class, 'harga'])->name('harga');
});

// ==========================================
// 2. PORTAL PENGURUS / ADMIN (5 FITUR UTAMA)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role:admin_or_petugas'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Fitur 1 Pengurus: Mengelola Data Nasabah (CRUD + Riwayat Transaksi)
    Route::get('/nasabah', [AdminNasabah::class, 'index'])->name('nasabah.index');
    Route::post('/nasabah', [AdminNasabah::class, 'store'])->name('nasabah.store');
    Route::get('/nasabah/{id}', [AdminNasabah::class, 'show'])->name('nasabah.show');
    Route::put('/nasabah/{id}', [AdminNasabah::class, 'update'])->name('nasabah.update');
    Route::delete('/nasabah/{id}', [AdminNasabah::class, 'destroy'])->name('nasabah.destroy');

    // Fitur 2 Pengurus: Mengelola Jenis dan Harga Sampah
    Route::get('/sampah', [AdminWasteItem::class, 'index'])->name('sampah.index');
    Route::post('/sampah', [AdminWasteItem::class, 'store'])->name('sampah.store');
    Route::put('/sampah/{id}', [AdminWasteItem::class, 'update'])->name('sampah.update');
    Route::delete('/sampah/{id}', [AdminWasteItem::class, 'destroy'])->name('sampah.destroy');
    Route::post('/sampah/{id}/toggle', [AdminWasteItem::class, 'toggle'])->name('sampah.toggle');
    Route::post('/kategori', [AdminWasteItem::class, 'storeCategory'])->name('kategori.store');

    // Fitur 3 Pengurus: Mencatat Setoran Sampah (Hitung nilai setoran & tambah saldo nasabah)
    Route::get('/timbang', [AdminTimbang::class, 'index'])->name('timbang.index');
    Route::post('/timbang', [AdminTimbang::class, 'store'])->name('timbang.store');
    Route::get('/timbang/struk/{id}', [AdminTimbang::class, 'receipt'])->name('timbang.receipt');

    // Fitur 4 Pengurus: Memproses Penarikan Saldo (Lihat permintaan, proses, kurangi saldo nasabah)
    Route::get('/penarikan', [AdminWithdrawal::class, 'index'])->name('penarikan.index');
    Route::post('/penarikan/{id}/approve', [AdminWithdrawal::class, 'approve'])->name('penarikan.approve');
    Route::post('/penarikan/{id}/reject', [AdminWithdrawal::class, 'reject'])->name('penarikan.reject');

    // Fitur 5 Pengurus: Melihat Laporan Transaksi (Setoran & Penarikan)
    Route::get('/laporan', [AdminReport::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [AdminReport::class, 'exportCsv'])->name('laporan.export');
});

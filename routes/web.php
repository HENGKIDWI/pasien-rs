<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Impor Controller
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\AntrianController as DokterAntrianController;
use App\Http\Controllers\Dokter\GajiController as DokterGajiController;
use App\Http\Controllers\Pengunjung\JadwalController;
use App\Http\Controllers\Pengunjung\AntrianController as PengunjungAntrianController;
use App\Http\Controllers\Pengunjung\DashboardController as PengunjungDashboardController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('beranda');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return $role === 'dokter' ? redirect()->route('dokter.dashboard') : redirect()->route('pengunjung.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== RUTE DOKTER =====
    Route::middleware(['auth'])->prefix('dokter')->name('dokter.')->group(function () {
        Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');
        Route::get('/antrian', [DokterAntrianController::class, 'index'])->name('antrian');
        
        Route::post('/antrian/{antrian}/panggil', [DokterAntrianController::class, 'panggil'])->name('antrian.panggil');
        Route::post('/antrian/{antrian}/selesai', [DokterAntrianController::class, 'selesai'])->name('antrian.selesai');
        
        // PENAMBAHAN RUTE BARU UNTUK HALAMAN GAJI
        Route::get('/gaji', [DokterGajiController::class, 'index'])->name('gaji');
    });

    // ===== RUTE PENGUNJUNG =====
    Route::middleware(['auth'])->prefix('pengunjung')->name('pengunjung.')->group(function () {
        Route::get('/dashboard', [PengunjungDashboardController::class, 'index'])->name('dashboard');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
        Route::get('/status', [PengunjungAntrianController::class, 'status'])->name('status');
        Route::get('/riwayat', [PengunjungAntrianController::class, 'riwayat'])->name('riwayat');
        Route::post('/ambil-antrian', [PengunjungAntrianController::class, 'ambil'])->name('ambil-antrian');
        Route::post('/antrian/{id}/batalkan', [PengunjungAntrianController::class, 'batalkan'])->name('antrian.batalkan');
        Route::get('/jadwal-by-poli/{poli_id}', [PengunjungAntrianController::class, 'getJadwalByPoli'])->name('jadwal.by.poli');
    });
});

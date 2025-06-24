<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Impor Controller Fitur Anda
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\AntrianController as DokterAntrianController;
use App\Http\Controllers\Dokter\GajiController as DokterGajiController;
use App\Http\Controllers\Pengunjung\JadwalController;
use App\Http\Controllers\Pengunjung\AntrianController;
use App\Http\Controllers\Pengunjung\DashboardController;
use App\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Rute untuk Tamu (Guest)
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('beranda');

// Penting! File auth.php harus berada di sini, di luar middleware 'auth'
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Rute Terotentikasi (Untuk Pengguna yang Sudah Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute '/dashboard' utama untuk mengarahkan berdasarkan peran
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        if ($role === 'dokter') {
            return redirect()->route('dokter.dashboard');
        }

        // Asumsikan selain dokter adalah pengunjung
        return redirect()->route('pengunjung.dashboard');

    })->name('dashboard');

    // Rute untuk profile pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== RUTE FITUR UNTUK DOKTER =====
    Route::middleware(['auth'])->prefix('dokter')->name('dokter.')->group(function () {
        Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dashboard');
        Route::get('/antrian', [DokterAntrianController::class, 'index'])->name('antrian');
        Route::post('/antrian/{id}/selesai', [DokterAntrianController::class, 'markSelesai'])->name('antrian.selesai');
        Route::get('/gaji', [DokterGajiController::class, 'index'])->name('gaji');
    });


    // ===== RUTE FITUR UNTUK PENGUNJUNG =====
    // Menambahkan ->name('pengunjung.') akan membuat nama rute menjadi konsisten
    // Contoh: 'pengunjung.dashboard', 'pengunjung.ambil-antrian', dll.
    Route::middleware(['auth'])->prefix('pengunjung')->name('pengunjung.')->group(function () {

        // Dashboard utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Lihat jadwal dokter
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
        
        // Lihat status antrian saat ini
        Route::get('/status', [AntrianController::class, 'status'])->name('status');
        
        // Riwayat kunjungan
        Route::get('/riwayat', [AntrianController::class, 'riwayat'])->name('riwayat');

        // --- Rute Terkait Antrian ---
        Route::post('/ambil-antrian', [AntrianController::class, 'ambil'])->name('ambil-antrian');
        Route::post('/antrian/{id}/batalkan', [AntrianController::class, 'batalkan'])->name('antrian.batalkan');

        // Rute untuk mengambil jadwal berdasarkan poli via AJAX
        Route::get('/jadwal-by-poli/{poli_id}', [AntrianController::class, 'getJadwalByPoli'])->name('jadwal.by.poli');

    });
});

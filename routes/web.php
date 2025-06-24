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
|
| Rute-rute ini dapat diakses oleh siapa saja, baik yang sudah login
| maupun yang belum. Di sinilah tempat rute login, register, dll.
|
*/



Route::get('/', [LandingController::class, 'index'])->name('beranda');

// Penting! File auth.php harus berada di sini, di luar middleware 'auth'
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Rute Terotentikasi (Untuk Pengguna yang Sudah Login)
|--------------------------------------------------------------------------
|
| Semua rute di dalam grup ini dilindungi. Hanya pengguna yang sudah
| login yang bisa mengaksesnya.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Rute '/dashboard' utama untuk mengarahkan berdasarkan peran
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        if ($role === 'dokter') {
            return redirect()->route('dokter.dashboard'); // Gunakan name() untuk redirect
        }

        // Asumsikan selain dokter adalah pengunjung
        return redirect()->route('pengunjung.dashboard'); // Gunakan name() untuk redirect

    })->name('dashboard');

    // Rute untuk profile pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== RUTE FITUR UNTUK DOKTER =====
    Route::middleware(['auth'])->prefix('dokter')->group(function () {
        Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dokter.dashboard');
        Route::get('/antrian', [DokterAntrianController::class, 'index'])->name('dokter.antrian');
        Route::post('/antrian/{id}/selesai', [DokterAntrianController::class, 'markSelesai'])->name('dokter.antrian.selesai');
        Route::get('/gaji', [DokterGajiController::class, 'index'])->name('dokter.gaji');
    });


    // ===== RUTE FITUR UNTUK PENGUNJUNG =====
    Route::middleware(['auth'])->prefix('pengunjung')->group(function () {

        // Dashboard utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('pengunjung.dashboard');

        // Lihat jadwal dokter
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('pengunjung.jadwal');

        // --- Rute Terkait Antrian ---
        Route::post('/ambil-antrian', [AntrianController::class, 'ambil'])->name('ambil-antrian');
        Route::post('/antrian/{id}/batalkan', [AntrianController::class, 'batalkan'])->name('antrian.batalkan');

        // Rute BARU untuk mengambil jadwal berdasarkan poli
        Route::get('/jadwal-by-poli/{poli_id}', [AntrianController::class, 'getJadwalByPoli'])->name('jadwal.by.poli');

        // Lihat status antrian saat ini
        Route::get('/status', [AntrianController::class, 'status'])->name('pengunjung.status');
        // Riwayat kunjungan
        Route::get('/riwayat', [AntrianController::class, 'riwayat'])->name('pengunjung.riwayat');
    });
});

<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Menampilkan halaman jadwal dokter lengkap.
     */
    public function index()
    {
        // Mengambil semua data jadwal dari VIEW yang sudah dioptimalkan.
        $jadwal = DB::table('v_jadwal_dokter_lengkap')
                    ->orderBy('nama_poli')
                    ->orderBy('nama_dokter')
                    ->get()
                    // Mengelompokkan berdasarkan nama poli untuk tampilan yang lebih rapi.
                    ->groupBy('nama_poli'); 

        return view('pengunjung.jadwal', compact('jadwal'));
    }
}

<?php
namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        // Menggunakan view v_jadwal_dokter_lengkap untuk data yang lebih efisien dan lengkap
        $jadwal = DB::table('v_jadwal_dokter_lengkap')->get();

        return view('pengunjung.jadwal', compact('jadwal'));
    }
}
<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Dokter;

class GajiController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id() > 1000000 ? Auth::id() - 1000000 : Auth::id();
        $bulanSekarang = now()->format('Y-m');

        // Jalankan SP untuk menghitung dan menyimpan gaji dokter bulan ini
        DB::statement('CALL sp_hitung_gaji_dokter_bulanan_satu(?, ?)', [$dokterId, $bulanSekarang]);

        // Ambil data gaji bulan ini dari tabel gaji_dokter (hasil SP)
        $gaji = DB::table('gaji_dokter')
            ->where('dokter_id', $dokterId)
            ->where('bulan', $bulanSekarang)
            ->first();

        $gajiBulanIni = $gaji->total_gaji ?? 0;

        // Ambil riwayat gaji dari view
        $riwayatGaji = DB::table('v_gaji_dokter_detail')
            ->where('dokter_id', $dokterId)
            ->orderByDesc('bulan')
            ->get();

        return view('dokter.gaji', compact('gajiBulanIni', 'riwayatGaji'));
    }
}

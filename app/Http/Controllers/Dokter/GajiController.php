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
        $today = today();

        // 1. Perhitungan Gaji Real-time untuk Bulan Ini
        $dokter = Dokter::find($dokterId);
        $gajiPokok = $dokter->gaji_pokok ?? 0;

        $totalPendapatanKonsultasi = DB::table('antrian as a')
                                    ->join('jadwal_dokter as jd', function($join) {
                                        $join->on('a.dokter_id', '=', 'jd.dokter_id')
                                             ->whereRaw('jd.hari = CASE DAYOFWEEK(a.tanggal_kunjungan) WHEN 1 THEN "minggu" WHEN 2 THEN "senin" WHEN 3 THEN "selasa" WHEN 4 THEN "rabu" WHEN 5 THEN "kamis" WHEN 6 THEN "jumat" WHEN 7 THEN "sabtu" END');
                                    })
                                    ->where('a.dokter_id', $dokterId)
                                    ->where('a.status_antrian', 'selesai')
                                    ->whereYear('a.tanggal_kunjungan', $today->year)
                                    ->whereMonth('a.tanggal_kunjungan', $today->month)
                                    ->sum('jd.biaya_konsultasi');
        
        $komisi = $totalPendapatanKonsultasi * 0.5;
        $gajiBulanIni = $gajiPokok + $komisi;
        
        // 2. Mengambil Riwayat Gaji dari v_gaji_dokter_detail
        $riwayatGaji = DB::table('v_gaji_dokter_detail')
            ->where('dokter_id', $dokterId)
            ->orderByDesc('bulan')
            ->get();

        return view('dokter.gaji', compact('gajiBulanIni', 'riwayatGaji'));
    }
}

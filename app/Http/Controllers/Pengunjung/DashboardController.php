<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pengunjungId = Auth::id();
        $today = today()->toDateString();

        // Menggunakan Stored Procedure untuk status antrian
        $antrianResult = DB::select('CALL sp_cek_status_antrian(?, ?)', [$pengunjungId, $today]);
        $antrianAktif = $antrianResult[0] ?? null;

        if ($antrianAktif) {
            $antrianAktif->id = DB::table('antrian')
                ->where('nomor_antrian', $antrianAktif->nomor_antrian)
                ->where('pengunjung_id', $pengunjungId)
                ->whereDate('tanggal_kunjungan', $today)
                ->value('id');
        }

        // Menggunakan View untuk daftar dokter di kartu
        $dokterList = DB::table('v_jadwal_dokter_lengkap')
            ->select('nama_dokter', 'gelar', 'nama_spesialisasi')
            ->distinct('nama_dokter')
            ->take(3)
            ->get();
            
        // Mengambil daftar Poli untuk modal
        $allPoli = DB::table('poli')->where('status_aktif', 'aktif')->get();

        // Mengambil riwayat kunjungan
        $riwayatList = DB::table('antrian as a')
            ->join('dokter as d', 'a.dokter_id', '=', 'd.id')
            ->select('a.tanggal_kunjungan', 'd.nama_dokter')
            ->where('a.pengunjung_id', $pengunjungId)
            ->where('a.status_antrian', 'selesai')
            ->orderByDesc('a.tanggal_kunjungan')
            ->limit(2)
            ->get();

        return view('pengunjung.dashboard', [
            'antrian' => $antrianAktif,
            'dokterList' => $dokterList,
            'allPoli' => $allPoli, // Mengirim data Poli ke view
            'riwayatList' => $riwayatList,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk pengunjung dengan semua data yang diperlukan.
     */
    public function index()
    {
        $pengunjungId = Auth::id();
        $today = today()->toDateString();

        // 1. Manfaatkan Stored Procedure `sp_cek_status_antrian` untuk data antrian aktif.
        //    SP ini sudah menyertakan semua kolom yang dibutuhkan, termasuk `id`.
        $antrianResult = DB::select('CALL sp_cek_status_antrian(?, ?)', [$pengunjungId, $today]);
        $antrianAktif = $antrianResult[0] ?? null;

        // Langkah manual untuk mencari ID tidak lagi diperlukan karena SP sudah menyediakannya.
        // Ini menghilangkan potensi kesalahan dalam mencocokkan ID antrian.

        // 2. Manfaatkan VIEW `v_jadwal_dokter_lengkap` untuk daftar dokter.
        $dokterList = DB::table('v_jadwal_dokter_lengkap')
            ->select('nama_dokter', 'gelar', 'nama_spesialisasi')
            ->distinct('nama_dokter')
            ->take(3)
            ->get();
            
        // 3. Ambil semua poli yang aktif untuk ditampilkan di form modal.
        $allPoli = DB::table('poli')
            ->where('status_aktif', 'aktif')
            ->get();

        // 4. Query manual untuk riwayat kunjungan.
        $riwayatList = DB::table('antrian as a')
            ->join('dokter as d', 'a.dokter_id', '=', 'd.id')
            ->select('a.tanggal_kunjungan', 'd.nama_dokter')
            ->where('a.pengunjung_id', $pengunjungId)
            ->where('a.status_antrian', 'selesai')
            ->orderByDesc('a.tanggal_kunjungan')
            ->limit(2)
            ->get();

        // Mengirim semua data yang diperlukan ke view.
        return view('pengunjung.dashboard', [
            'antrian' => $antrianAktif,
            'dokterList' => $dokterList,
            'allPoli' => $allPoli,
            'riwayatList' => $riwayatList,
        ]);
    }
}

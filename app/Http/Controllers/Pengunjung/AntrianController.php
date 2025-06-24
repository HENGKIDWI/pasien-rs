<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO; // Diperlukan untuk binding parameter PDO

class AntrianController extends Controller
{
    /**
     * Memproses permintaan untuk mengambil nomor antrian baru.
     * Metode ini akan memanggil Stored Procedure `sp_ambil_antrian`.
     */
    public function ambil(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        $pengunjung_id = Auth::id();
        $dokter_id = $request->dokter_id;
        $tanggal_kunjungan = now()->toDateString(); // Antrian selalu untuk hari ini
        $keluhan = $request->keluhan ?? '';

        try {
            // Menggunakan PDO untuk memanggil Stored Procedure dengan parameter OUT
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("CALL sp_ambil_antrian(?, ?, ?, ?, @p_nomor_antrian, @p_estimasi_waktu, @p_status)");
            
            // Binding parameter IN
            $stmt->bindParam(1, $pengunjung_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $dokter_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $tanggal_kunjungan, PDO::PARAM_STR);
            $stmt->bindParam(4, $keluhan, PDO::PARAM_STR);
            
            $stmt->execute();
            $stmt->closeCursor();

            // Mengambil hasil dari variabel OUT yang di-set oleh Stored Procedure
            $result = $pdo->query("SELECT @p_nomor_antrian as nomor_antrian, @p_estimasi_waktu as estimasi, @p_status as status")->fetch(PDO::FETCH_ASSOC);

            // Memeriksa status hasil dari Stored Procedure
            if (str_starts_with($result['status'], 'SUCCESS')) {
                return redirect()->route('pengunjung.dashboard')->with('success', 'Antrian ' . $result['nomor_antrian'] . ' berhasil diambil!');
            } else {
                // Mengambil pesan error yang informatif dari Stored Procedure
                $errorMessage = str_replace('ERROR: ', '', $result['status']);
                return redirect()->back()->with('error', 'Gagal mengambil antrian: ' . $errorMessage)->withInput();
            }

        } catch (\Throwable $e) {
            // Menangani error teknis jika terjadi
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Membatalkan antrian yang sudah ada.
     * Metode ini akan memanggil Stored Procedure `sp_batalkan_antrian`.
     */
    public function batalkan($id)
    {
        $pengunjung_id = Auth::id();

        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("CALL sp_batalkan_antrian(?, ?, @p_status)");
            
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $pengunjung_id, PDO::PARAM_INT);

            $stmt->execute();
            $stmt->closeCursor();

            $result = $pdo->query("SELECT @p_status as status")->fetch(PDO::FETCH_ASSOC);

            if (str_starts_with($result['status'], 'SUCCESS')) {
                return redirect()->route('pengunjung.dashboard')->with('success', 'Antrian berhasil dibatalkan.');
            } else {
                $errorMessage = str_replace('ERROR: ', '', $result['status']);
                return redirect()->back()->with('error', 'Gagal membatalkan: ' . $errorMessage);
            }
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    /**
     * Menampilkan status antrian aktif milik pengguna.
     * Metode ini memanggil Stored Procedure `sp_cek_status_antrian`.
     */
    public function status()
    {
        $pengunjung_id = Auth::id();
        $tanggal_hari_ini = now()->toDateString();

        // Memanggil stored procedure untuk mendapatkan status antrian yang detail
        $status_antrian = DB::select('CALL sp_cek_status_antrian(?, ?)', [$pengunjung_id, $tanggal_hari_ini]);
        
        // Ambil hasil pertama, atau null jika tidak ada hasil
        $antrian = $status_antrian[0] ?? null;

        return view('pengunjung.status', compact('antrian'));
    }

    /**
     * Menampilkan riwayat kunjungan pengguna yang sudah selesai.
     */
    public function riwayat()
    {
        $pengunjung_id = Auth::id();

        $riwayat = DB::table('antrian as a')
            ->join('dokter as d', 'a.dokter_id', '=', 'd.id')
            ->select('a.id', 'a.tanggal_kunjungan', 'a.status_antrian', 'a.jam_kunjungan', 'd.nama_dokter', 'd.gelar')
            ->where('a.pengunjung_id', $pengunjung_id)
            ->where('a.status_antrian', 'selesai')
            ->orderByDesc('a.tanggal_kunjungan')
            ->paginate(10); // Menambahkan paginasi untuk halaman riwayat

        return view('pengunjung.riwayat', compact('riwayat'));
    }
}

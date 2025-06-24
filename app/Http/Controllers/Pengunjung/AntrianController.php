<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class AntrianController extends Controller
{
    /**
     * Mengambil daftar dokter yang tersedia di sebuah poli pada hari ini
     * beserta biaya konsultasinya.
     */
    public function getJadwalByPoli($poli_id)
    {
        $dayMap = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu'
        ];
        $todayIndonesian = $dayMap[now()->dayOfWeekIso];

        // Mengambil daftar dokter unik beserta biaya konsultasinya dari VIEW
        $jadwal = DB::table('v_jadwal_dokter_lengkap')
            ->where('poli_id', $poli_id)
            ->where('hari', $todayIndonesian)
            ->where('sisa_kuota_hari_ini', '>', 0)
            // Memastikan kita mengambil biaya konsultasi
            ->select('dokter_id', 'nama_dokter', 'gelar', 'biaya_konsultasi')
            ->distinct('dokter_id') // Hanya satu entri per dokter
            ->get();

        return response()->json($jadwal);
    }

    /**
     * Memproses permintaan untuk mengambil nomor antrian baru.
     */
    public function ambil(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        $pengunjung_id = Auth::id();
        $dokter_id = $request->dokter_id;
        $tanggal_kunjungan = now()->toDateString();
        $keluhan = $request->keluhan ?? '';

        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("CALL sp_ambil_antrian(?, ?, ?, ?, @p_nomor_antrian, @p_estimasi_waktu, @p_status)");

            $stmt->bindParam(1, $pengunjung_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $dokter_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $tanggal_kunjungan, PDO::PARAM_STR);
            $stmt->bindParam(4, $keluhan, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();

            $result = $pdo->query("SELECT @p_nomor_antrian as nomor_antrian, @p_estimasi_waktu as estimasi, @p_status as status")->fetch(PDO::FETCH_ASSOC);

            if (str_starts_with($result['status'], 'SUCCESS')) {
                return redirect()->route('pengunjung.dashboard')->with('success', 'Antrian ' . $result['nomor_antrian'] . ' berhasil diambil!');
            } else {
                $errorMessage = str_replace('ERROR: ', '', $result['status']);
                return redirect()->back()->with('error', 'Gagal mengambil antrian: ' . $errorMessage)->withInput();
            }

        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Membatalkan antrian yang sudah ada.
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
     */
    public function status()
    {
        $pengunjung_id = Auth::id();
        $tanggal_hari_ini = now()->toDateString();

        $status_antrian = DB::select('CALL sp_cek_status_antrian(?, ?)', [$pengunjung_id, $tanggal_hari_ini]);
        $antrian = $status_antrian[0] ?? null;

        return view('pengunjung.status', compact('antrian'));
    }

    /**
     * Menampilkan riwayat kunjungan pengguna yang sudah selesai.
     */
    public function riwayat()
    {
        $pengunjungId = Auth::id();

        // PERBAIKAN: Mengambil data dari VIEW baru yang lebih efisien
        $riwayat = DB::table('v_riwayat_kunjungan')
            ->where('pengunjung_id', $pengunjungId)
            ->orderByDesc('tanggal_kunjungan')
            ->paginate(10); // Menggunakan paginasi untuk membatasi data per halaman

        return view('pengunjung.riwayat', compact('riwayat'));
    }
}

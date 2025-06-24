<?php
namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Dokter; // Import model Dokter

class DashboardController extends Controller
{
    public function index()
    {
        // Mengembalikan ID dokter ke ID asli
        $dokterId = Auth::id() > 1000000 ? Auth::id() - 1000000 : Auth::id();
        $today = today();

        // 1. Mengambil SEMUA antrian hari ini dari VIEW untuk daftar di tabel
        $antrianHariIni = DB::table('v_daftar_antrian_harian')
                            ->where('dokter_id', $dokterId)
                            ->whereDate('tanggal_kunjungan', $today)
                            ->orderBy('jam_mulai', 'asc')
                            ->get();

        // 2. Menghitung data untuk kartu ringkasan dari koleksi di atas
        $totalAntrian = $antrianHariIni->count();
        $antrianSelesai = $antrianHariIni->where('status_antrian', 'selesai')->count();
        $antrianMenunggu = $antrianHariIni->whereIn('status_antrian', ['menunggu', 'dipanggil'])->count();
        $antrianBatal = $antrianHariIni->where('status_antrian', 'batal')->count();

        // 3. Mengambil jumlah antrian aktif secara real-time dari VIEW v_antrian_aktif
        $antrianAktifCount = DB::table('v_antrian_aktif')
                                ->where('dokter_id', $dokterId)
                                ->whereDate('tanggal_kunjungan', $today)
                                ->count();
        
        // 4. PERHITUNGAN GAJI REAL-TIME UNTUK BULAN INI
        $dokter = Dokter::find($dokterId);
        $gajiPokok = $dokter->gaji_pokok ?? 0;

        // Hitung total pendapatan dari biaya konsultasi yang sudah 'selesai' bulan ini
        $totalPendapatanKonsultasi = DB::table('antrian as a')
                                    ->join('jadwal_dokter as jd', function($join) {
                                        $join->on('a.dokter_id', '=', 'jd.dokter_id')
                                             // Menggunakan metode yang aman untuk mencocokkan hari
                                             ->whereRaw('jd.hari = CASE DAYOFWEEK(a.tanggal_kunjungan)
                                                                WHEN 1 THEN "minggu"
                                                                WHEN 2 THEN "senin"
                                                                WHEN 3 THEN "selasa"
                                                                WHEN 4 THEN "rabu"
                                                                WHEN 5 THEN "kamis"
                                                                WHEN 6 THEN "jumat"
                                                                WHEN 7 THEN "sabtu"
                                                            END');
                                    })
                                    ->where('a.dokter_id', $dokterId)
                                    ->where('a.status_antrian', 'selesai')
                                    ->whereYear('a.tanggal_kunjungan', $today->year)
                                    ->whereMonth('a.tanggal_kunjungan', $today->month)
                                    ->sum('jd.biaya_konsultasi');
        
        // Asumsi komisi dokter 50% (sesuai SP sp_hitung_gaji_dokter_bulanan)
        $komisi = $totalPendapatanKonsultasi * 0.5;
        $gajiBulanIni = $gajiPokok + $komisi;
        
        return view('dokter.dashboard', [
            'antrianHariIni' => $antrianHariIni,
            'totalAntrian' => $totalAntrian,
            'antrianSelesai' => $antrianSelesai,
            'antrianMenunggu' => $antrianMenunggu,
            'antrianBatal' => $antrianBatal,
            'antrianAktifCount' => $antrianAktifCount,
            'gajiBulanIni' => $gajiBulanIni, // Kirim data gaji bulan ini
        ]);
    }
}

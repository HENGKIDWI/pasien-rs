<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    /**
     * Menampilkan semua antrian untuk dokter yang login.
     */
    public function index()
    {
        $dokterId = Auth::id() > 1000000 ? Auth::id() - 1000000 : Auth::id();

        // PERBAIKAN: Nama variabel diubah dari '$antrian' menjadi '$antrianHariIni'
        // agar sesuai dengan yang diharapkan oleh view.
        $antrianHariIni = DB::table('v_daftar_antrian_harian')
                     ->where('dokter_id', $dokterId)
                     // Hanya menampilkan data hari ini di halaman antrian
                     ->whereDate('tanggal_kunjungan', today())
                     ->orderBy('status_antrian', 'desc') // Menampilkan yang dipanggil/menunggu di atas
                     ->orderBy('jam_mulai', 'asc')
                     ->get();

        // Mengirim variabel dengan nama yang sudah diperbaiki
        return view('dokter.antrian', compact('antrianHariIni'));
    }

    /**
     * Mengubah status antrian menjadi 'dipanggil'.
     */
    public function panggil(Antrian $antrian)
    {
        $dokterId = Auth::id() > 1000000 ? Auth::id() - 1000000 : Auth::id();
        if ($antrian->dokter_id != $dokterId) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $antrian->status_antrian = 'dipanggil';
        $antrian->save();

        // Redirect kembali ke halaman antrian, bukan ke dasbor
        return redirect()->route('dokter.antrian')->with('success', "Pasien dengan nomor antrian {$antrian->nomor_antrian} telah dipanggil.");
    }

    /**
     * Menyelesaikan konsultasi dengan memanggil Stored Procedure.
     */
    public function selesai(Antrian $antrian)
    {
        $dokterId = Auth::id() > 1000000 ? Auth::id() - 1000000 : Auth::id();
        if ($antrian->dokter_id != $dokterId) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        DB::statement('CALL sp_selesaikan_antrian(?, ?)', [$antrian->id, $dokterId]);

        // Redirect kembali ke halaman antrian, bukan ke dasbor
        return redirect()->route('dokter.antrian')->with('success', "Konsultasi dengan nomor antrian {$antrian->nomor_antrian} telah selesai.");
    }
}

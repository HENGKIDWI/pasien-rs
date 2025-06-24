<?php
namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    // Tampilkan daftar antrian hari ini
    public function index()
    {
        $dokter_id = Auth::id();

        $antrian = DB::table('antrian as a')
            ->join('pengunjung as p', 'a.pengunjung_id', '=', 'p.id')
            ->select('a.*', 'p.nama_lengkap as nama_pengunjung')
            ->where('a.dokter_id', $dokter_id)
            ->whereDate('a.tanggal_kunjungan', now()->toDateString())
            ->orderBy('a.jam_kunjungan')
            ->get();

        return view('dokter.antrian', compact('antrian'));
    }

    // Tandai antrian selesai
    public function markSelesai($id)
    {
        DB::table('antrian')->where('id', $id)->update([
            'status_antrian' => 'selesai'
        ]);

        return redirect()->route('dokter.antrian')->with('success', 'Antrian ditandai selesai.');
    }
}

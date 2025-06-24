<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    public function index()
    {
        $dokter_id = Auth::id();

        $gaji = DB::table('v_gaji_dokter_detail')
            ->where('dokter_id', $dokter_id)
            ->orderByDesc('bulan')
            ->get();

        return view('dokter.gaji', compact('gaji'));
    }
}

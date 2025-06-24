<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RumahSakit;
use App\Models\Pengumuman;
use App\Models\Dokter;

class LandingController extends Controller
{
    public function index()
    {
        $rs = RumahSakit::first(); // info RS
        $pengumuman = Pengumuman::latest()->take(3)->get(); // 3 terbaru
        $dokter = Dokter::with('spesialisasi')->where('status_aktif', 'aktif')->get();

        return view('welcome', compact('rs', 'pengumuman', 'dokter'));
    }
}

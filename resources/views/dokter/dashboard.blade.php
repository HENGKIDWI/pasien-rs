@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="bg-gradient-to-r from-slate-700 to-slate-900 rounded-2xl text-white p-8 mb-8 shadow-lg">
            <h2 class="text-3xl font-bold mb-2">Dasbor Dokter</h2>
            <p class="text-slate-300">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <h4 class="font-bold text-gray-500 mb-2 flex items-center"><i class="bi bi-people-fill mr-2"></i>Pasien Hari Ini</h4>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalAntrian }}</p>
                        <p class="text-sm text-gray-500">Total</p>
                    </div>
                     <div>
                        <p class="text-3xl font-bold text-yellow-600">{{ $antrianMenunggu }}</p>
                        <p class="text-sm text-gray-500">Menunggu</p>
                    </div>
                     <div>
                        <p class="text-3xl font-bold text-green-600">{{ $antrianSelesai }}</p>
                        <p class="text-sm text-gray-500">Selesai</p>
                    </div>
                     <div>
                        <p class="text-3xl font-bold text-red-600">{{ $antrianBatal }}</p>
                        <p class="text-sm text-gray-500">Batal</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-gray-500 mb-2 flex items-center"><i class="bi bi-wallet2 mr-2"></i>Estimasi Gaji Bulan Ini</h4>
                    <p class="text-4xl font-bold text-green-600">{{ 'Rp ' . number_format($gajiBulanIni, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Gaji Pokok + Komisi (real-time)</p>
                </div>
                <a href="{{ route('dokter.gaji') }}" class="mt-4 w-full text-center px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition duration-300">
                    Lihat Rincian Gaji <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col justify-between">
                 <div>
                    <h4 class="font-bold text-gray-500 mb-2 flex items-center"><i class="bi bi-card-checklist mr-2"></i>Manajemen Antrian</h4>
                    <p class="text-sm text-gray-600">Panggil pasien, selesaikan konsultasi, dan lihat daftar lengkap pasien Anda untuk hari ini.</p>
                </div>
                <a href="{{ route('dokter.antrian') }}" class="mt-4 w-full text-center px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition duration-300">
                    Buka Halaman Antrian <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

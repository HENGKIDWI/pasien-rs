@extends('layouts.app')

@section('content')
@php
    $antrianHariIni = $antrianHariIni ?? collect();
    $gajiBulanIni = $gajiBulanIni ?? null;
    $riwayatAntrian = $riwayatAntrian ?? collect();
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl text-white p-8 mb-8 shadow-lg">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, dr. {{ Auth::user()->name }}!</h2>
            <p class="text-blue-100">Dashboard dokter - pantau antrian pasien dan ringkasan gaji Anda.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Kartu Antrian Hari Ini --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-people text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Antrian Hari Ini</h3>
                        <p class="text-gray-600">Daftar pasien yang akan Anda layani hari ini</p>
                    </div>
                </div>
                <div class="space-y-3 flex-grow">
                    @forelse ($antrianHariIni as $antrian)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $antrian->nama_pengunjung ?? '-' }}</div>
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($antrian->jam_kunjungan)->format('H:i') }} - {{ ucfirst($antrian->status_antrian) }}</div>
                        </div>
                        <div class="text-blue-600 text-sm font-medium">{{ $antrian->nomor_antrian }}</div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Belum ada antrian hari ini.</p>
                    @endforelse
                </div>
                <a href="{{ route('dokter.antrian') }}" class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center justify-center">
                    Lihat Semua Antrian <i class="bi bi-arrow-right-short ml-2"></i>
                </a>
            </div>
            {{-- Kartu Gaji Bulan Ini --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-cash-stack text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Gaji Bulan Ini</h3>
                        <p class="text-gray-600">Ringkasan gaji bulan berjalan</p>
                    </div>
                </div>
                <div class="flex-grow flex flex-col justify-center items-center">
                    <div class="text-3xl font-bold text-yellow-600 mb-2">
                        Rp{{ number_format($gajiBulanIni->total_gaji ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="text-gray-600">Periode: {{ $gajiBulanIni->bulan ?? date('F Y') }}</div>
                </div>
                <a href="{{ route('dokter.gaji') }}" class="w-full mt-4 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 flex items-center justify-center">
                    Lihat Detail Gaji <i class="bi bi-arrow-right-short ml-2"></i>
                </a>
            </div>
            {{-- Kartu Riwayat Antrian (opsional) --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col md:col-span-2">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-clock-history text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-gray-900">Riwayat Antrian</h3>
                        <p class="text-gray-600">Pasien yang sudah Anda layani</p>
                    </div>
                </div>
                <div class="space-y-3 flex-grow">
                    @forelse ($riwayatAntrian as $riwayat)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $riwayat->nama_pengunjung ?? '-' }}</div>
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($riwayat->tanggal_kunjungan)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="text-green-600 text-sm font-medium">Selesai</div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">Belum ada riwayat antrian.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

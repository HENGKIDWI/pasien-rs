@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Status Antrian Anda</h2>

        @if ($antrian)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                {{-- Detail Dokter dan RS --}}
                <div class="text-center border-b pb-6 mb-6">
                    <p class="text-gray-600">Anda sedang dalam antrian untuk:</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $antrian->gelar }} {{ $antrian->nama_dokter }}</h3>
                    <p class="text-sm text-gray-500">{{ $antrian->nama_spesialisasi }}</p>
                    <p class="text-sm text-gray-500 mt-2"><i class="bi bi-hospital"></i> {{ $antrian->nama_rs }}</p>
                </div>
                
                {{-- Nomor Antrian Besar --}}
                <div class="text-center my-8">
                    <p class="text-lg text-gray-500">Nomor Antrian Anda</p>
                    <p class="text-8xl font-bold text-blue-600 tracking-tight">{{ $antrian->nomor_antrian }}</p>
                </div>

                {{-- Status Real-time dengan Visual --}}
                @if ($antrian->status_antrian == 'dipanggil')
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-6 rounded-lg text-center">
                        <i class="bi bi-megaphone-fill text-4xl"></i>
                        <p class="text-2xl font-bold mt-2">Sekarang Giliran Anda!</p>
                        <p>Silakan menuju ke ruang periksa dokter.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500 text-sm">Posisi Anda Saat Ini</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $antrian->posisi_saat_ini }}</p>
                        </div>
                         <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-500 text-sm">Perkiraan Waktu Tunggu</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $antrian->estimasi_waktu_tunggu }} <span class="text-lg font-medium">menit</span></p>
                        </div>
                    </div>

                    {{-- Progress Bar Status --}}
                    <div class="mt-8">
                        <p class="text-center text-sm font-medium text-gray-700 mb-2">Status: {{ ucfirst($antrian->status_antrian) }}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $antrian->status_antrian == 'menunggu' ? '33%' : '66%' }}"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>Terdaftar</span>
                            <span>Dipanggil</span>
                            <span>Selesai</span>
                        </div>
                    </div>
                @endif
                
            </div>
        @else
            {{-- Tampilan jika tidak ada antrian aktif --}}
            <div class="bg-white p-12 rounded-2xl shadow-lg text-center text-gray-500">
                <i class="bi bi-person-exclamation text-6xl"></i>
                <p class="mt-4 text-xl font-semibold">Anda tidak memiliki antrian aktif saat ini.</p>
                <p class="mt-2">Silakan kembali ke dasbor untuk mengambil nomor antrian baru.</p>
                <a href="{{ route('pengunjung.dashboard') }}" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    Kembali ke Dasbor
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Rincian Gaji</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Kartu Estimasi Gaji Bulan Ini --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h4 class="font-bold text-gray-500 mb-2 flex items-center"><i class="bi bi-wallet2 mr-2"></i>Estimasi Gaji Bulan Ini (Real-time)</h4>
                <p class="text-5xl font-bold text-green-600">{{ 'Rp ' . number_format($gajiBulanIni, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-2">Perhitungan berdasarkan Gaji Pokok Anda ditambah 50% komisi dari total biaya konsultasi yang telah selesai pada bulan {{ date('F Y') }}.</p>
            </div>
        </div>

        {{-- Riwayat Gaji --}}
        <div class="mt-12 bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Riwayat Gaji</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b-2 border-gray-200 bg-gray-50">
                        <tr>
                            <th class="p-4">Bulan</th>
                            <th class="p-4">Gaji Pokok</th>
                            <th class="p-4">Komisi Konsultasi</th>
                            <th class="p-4">Total Gaji Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatGaji as $gaji)
                            <tr class="border-b border-gray-100">
                                <td class="p-4 font-semibold">{{ \Carbon\Carbon::parse($gaji->bulan . '-01')->translatedFormat('F Y') }}</td>
                                <td class="p-4">{{ 'Rp ' . number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                <td class="p-4">{{ 'Rp ' . number_format($gaji->komisi_konsultasi, 0, ',', '.') }}</td>
                                <td class="p-4 font-bold text-green-700">{{ 'Rp ' . number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                             <tr>
                                <td colspan="4" class="text-center p-8 text-gray-500">
                                    <p>Belum ada riwayat gaji yang tercatat.</p>
                                    <p class="text-sm">Riwayat akan muncul setelah proses penggajian akhir bulan dijalankan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

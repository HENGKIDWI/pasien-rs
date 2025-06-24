@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Jadwal Praktik Dokter</h2>

        @if($jadwal->isEmpty())
            <div class="bg-white p-8 rounded-2xl shadow-lg text-center text-gray-500">
                <i class="bi bi-calendar-x text-5xl"></i>
                <p class="mt-4">Saat ini tidak ada jadwal dokter yang tersedia.</p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($jadwal as $namaPoli => $jadwalPoli)
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">{{ $namaPoli }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b-2 border-gray-200 bg-gray-50">
                                    <tr>
                                        <th class="p-4">Nama Dokter</th>
                                        <th class="p-4">Spesialisasi</th>
                                        <th class="p-4">Hari</th>
                                        <th class="p-4">Jam Praktik</th>
                                        <th class="p-4">Biaya Konsultasi</th>
                                        <th class="p-4 text-center">Sisa Kuota Hari Ini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwalPoli as $j)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="p-4 font-semibold text-gray-800">{{ $j->gelar }} {{ $j->nama_dokter }}</td>
                                            <td class="p-4">{{ $j->nama_spesialisasi }}</td>
                                            <td class="p-4 capitalize">{{ $j->hari }}</td>
                                            <td class="p-4">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</td>
                                            <td class="p-4 font-medium text-green-700">{{ 'Rp ' . number_format($j->biaya_konsultasi, 0, ',', '.') }}</td>
                                            <td class="p-4 text-center">
                                                 <span @class([
                                                    'px-3 py-1 text-xs font-bold rounded-full',
                                                    'bg-green-100 text-green-800' => $j->sisa_kuota_hari_ini > 5,
                                                    'bg-yellow-100 text-yellow-800' => $j->sisa_kuota_hari_ini <= 5 && $j->sisa_kuota_hari_ini > 0,
                                                    'bg-red-100 text-red-800' => $j->sisa_kuota_hari_ini <= 0,
                                                ])>
                                                    {{ $j->sisa_kuota_hari_ini }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

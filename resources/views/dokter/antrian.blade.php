@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Manajemen Antrian Pasien Hari Ini</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b-2 border-gray-200 bg-gray-50">
                        <tr>
                            <th class="p-4">No. Antrian</th>
                            <th class="p-4">Nama Pasien</th>
                            <th class="p-4">Jam Praktik</th>
                            <th class="p-4">Keluhan</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrianHariIni as $antrian)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-4 font-bold text-blue-600">{{ $antrian->nomor_antrian }}</td>
                                <td class="p-4">{{ $antrian->nama_pengunjung }}</td>
                                <td class="p-4">{{ \Carbon\Carbon::parse($antrian->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($antrian->jam_selesai)->format('H:i') }}</td>
                                <td class="p-4 text-sm text-gray-600">{{ Str::limit($antrian->keluhan, 40) ?: '-' }}</td>
                                <td class="p-4 text-center">
                                    <span @class([
                                        'px-3 py-1 text-xs font-medium rounded-full',
                                        'bg-yellow-100 text-yellow-800' => $antrian->status_antrian == 'menunggu',
                                        'bg-blue-100 text-blue-800' => $antrian->status_antrian == 'dipanggil',
                                        'bg-green-100 text-green-800' => $antrian->status_antrian == 'selesai',
                                        'bg-red-100 text-red-800' => $antrian->status_antrian == 'batal',
                                    ])>
                                        {{ ucfirst($antrian->status_antrian) }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if ($antrian->status_antrian == 'menunggu')
                                        <form action="{{ route('dokter.antrian.panggil', $antrian->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600">Panggil</button>
                                        </form>
                                    @elseif ($antrian->status_antrian == 'dipanggil')
                                        <form action="{{ route('dokter.antrian.selesai', $antrian->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">Selesaikan</button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-8 text-gray-500">
                                    <i class="bi bi-person-x-fill text-4xl"></i>
                                    <p class="mt-2">Tidak ada antrian untuk hari ini.</p>
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

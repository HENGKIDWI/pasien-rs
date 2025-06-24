@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Riwayat Kunjungan</h2>

        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b-2 border-gray-200 bg-gray-50">
                        <tr>
                            <th class="p-4">Tanggal Kunjungan</th>
                            <th class="p-4">Dokter</th>
                            <th class="p-4">Keluhan</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $item)
                            <tr class="border-b border-gray-100">
                                <td class="p-4 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
                                <td class="p-4">{{ $item->gelar }} {{ $item->nama_dokter }}</td>
                                <td class="p-4 text-sm text-gray-600">{{ $item->keluhan ?: '-' }}</td>
                                <td class="p-4 text-center">
                                    {{-- PERUBAHAN: Menambahkan ikon di sebelah teks status --}}
                                    <span @class([
                                        'inline-flex items-center px-3 py-1 text-xs font-medium rounded-full',
                                        'bg-green-100 text-green-800' => $item->status_antrian == 'selesai',
                                        'bg-red-100 text-red-800' => $item->status_antrian == 'batal',
                                    ])>
                                        @if($item->status_antrian == 'selesai')
                                            <i class="bi bi-check-circle-fill mr-1.5"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill mr-1.5"></i>
                                        @endif
                                        {{ ucfirst($item->status_antrian) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-8 text-gray-500">
                                    <i class="bi bi-journal-x text-5xl"></i>
                                    <p class="mt-4">Anda belum memiliki riwayat kunjungan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Menampilkan link Paginasi --}}
            <div class="mt-6">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

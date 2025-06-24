@extends('layouts.app')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Halo, {{ Auth::user()->name }}!</h2>

        @if(Auth::user()->role === 'dokter')
            <h3 class="text-lg font-semibold mb-2">Menu Dokter:</h3>
            <ul class="list-disc list-inside">
                <li><a href="{{ route('dokter.antrian') }}" class="text-blue-600 underline">Lihat Antrian Hari Ini</a></li>
                <li><a href="{{ route('dokter.jadwal') }}" class="text-blue-600 underline">Jadwal Praktek</a></li>
                <li><a href="{{ route('dokter.gaji') }}" class="text-blue-600 underline">Gaji Bulan Ini</a></li>
            </ul>
        @else
            <h3 class="text-lg font-semibold mb-2">Menu Pengunjung:</h3>
            <ul class="list-disc list-inside">
                <li><a href="{{ route('pengunjung.jadwal') }}" class="text-blue-600 underline">Lihat Jadwal Dokter</a></li>
                <li><a href="{{ route('pengunjung.antrian') }}" class="text-blue-600 underline">Ambil Antrian</a></li>
                <li><a href="{{ route('pengunjung.riwayat') }}" class="text-blue-600 underline">Riwayat Kunjungan</a></li>
            </ul>
        @endif
    </div>

@endsection
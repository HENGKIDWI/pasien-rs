@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Status Antrian Saya</h2>

    @if($antrian)
        <p><strong>Nomor Antrian:</strong> {{ $antrian->nomor_antrian }}</p>
        <p><strong>Dokter:</strong> {{ $antrian->nama_dokter }}</p>
        <p><strong>Status:</strong> {{ ucfirst($antrian->status_antrian) }}</p>
        <p><strong>Estimasi:</strong> {{ $antrian->estimasi_waktu_tunggu }} menit</p>
    @else
        <p>Anda belum mengambil antrian saat ini.</p>
    @endif
</div>
@endsection

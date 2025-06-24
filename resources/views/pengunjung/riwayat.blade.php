@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Riwayat Kunjungan</h2>

    @if($riwayat->isEmpty())
        <p>Tidak ada riwayat kunjungan.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $item)
                    <tr>
                        <td>{{ $item->nama_dokter }}</td>
                        <td>{{ $item->tanggal_kunjungan }}</td>
                        <td>{{ $item->jam_kunjungan }}</td>
                        <td>{{ $item->status_antrian }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
